<?php

namespace App\Http\Controllers;

use App\AccountingLedger;
use App\IncomeAndExpense;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use App\AccountingTransaction;
use App\Budget;
use App\BudgetAmount;
use App\Collection;
use App\CoopEmployee;
use App\CooperativeFinancialPeriod;
use App\Exports\TrialBalanceExport;
use App\Farmer;
use App\GroupLoan;
use App\Loan;
use App\SavingAccount;
use App\Wallet;
use DateTime;
use Illuminate\Support\Facades\Auth;
use EloquentBuilder;
use Log;
use DB;
use Exception;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;

class StatementsController extends Controller
{
    public function isndex()
    {
        $this->trialBalance();
        $coop = Auth::user()->cooperative->id;
        $data = get_statement_data($coop);
        // return view('pages.cooperative.accounting.trial_balance.report', compact('data'));
    }

    public function trialBalance($period_id, Request $request)
    {
        $user = Auth::user(); 
        try {

            $download = $request->query('download', 'pdf');

            $filter_ranges = get_financial_period_statement_ranges($period_id,$request->from, $request->to);
            $period = $filter_ranges["period"];
            $filter_data = $filter_ranges["filter_data"];
            $balance_bf = $filter_ranges["balance_bf"];
            $balance_cf = $filter_ranges["balance_cf"];

            $coopid = $user->cooperative_id;
            $startDate = $filter_data['from'];
            $endDate = $filter_data['to'];

            $compareStartDate = '';
            $compareEndDate = '';

            $records = [
                'assets' => $this->getLedgerBalances('Assets', $startDate, $endDate, $coopid),
                'expenses' => $this->getLedgerBalances('Expenses', $startDate, $endDate, $coopid),
                'equity' => $this->getLedgerBalances('Equity', $startDate, $endDate, $coopid),
                'liabilities' => $this->getLedgerBalances('Liabilities', $startDate, $endDate, $coopid),
                'revenue' => $this->getLedgerBalances('Revenue', $startDate, $endDate, $coopid),
            ];

            $compare_records = [];
            $compare = filter_var($request->input('compare'), FILTER_VALIDATE_INT);
            if (is_int($compare)) {
                $stArr = explode('-', $startDate);
                $edArr = explode('-', $endDate);
                $compareStartDate = sprintf('%d-%d-%d', $compare, $stArr[1], $stArr[2]);
                $compareEndDate = sprintf('%d-%d-%d', $compare, $edArr[1], $edArr[2]);

                $compare_records = [
                    'assets' => $this->getLedgerBalances('Assets', $compareStartDate, $compareEndDate, $coopid),
                    'expenses' => $this->getLedgerBalances('Expenses', $compareStartDate, $compareEndDate, $coopid),
                    'equity' => $this->getLedgerBalances('Equity', $compareStartDate, $compareEndDate, $coopid),
                    'liabilities' => $this->getLedgerBalances('Liabilities', $compareStartDate, $compareEndDate, $coopid),
                    'revenue' => $this->getLedgerBalances('Revenue', $compareStartDate, $compareEndDate, $coopid),
                ];
            } 

            if ($download == 'pdf') {

                $pdf = app('dompdf.wrapper');
                $pdf->setPaper('a4');
                $pdf->loadView('pdfs.reports.trial_balance', compact('startDate', 'endDate', 'compareStartDate', 'compareEndDate', 'records', 'compare_records'));

                return $pdf->download('TrialBalance.pdf');

            }

            if ($download == 'excel') {

                $cooperativeName = $user->cooperative->name;
                $debitSum = 0; 
                $creditSum = 0;
                $compareDebitSum = 0;
                $compareCreditSum = 0;
                $hasCompare = count($compare_records) > 0 ? true : false;

                $excel = new Spreadsheet();
                $excel->getProperties()
                    ->setCreator($cooperativeName)
                    ->setTitle('TrialBalance');

                $sheet = $excel->getActiveSheet()->setTitle('Balance Sheet');

                $styleArray = array('font' => array('bold' => true));

                $sheet->getStyle('A1')->applyFromArray($styleArray);
                $sheet->getStyle('A2')->applyFromArray($styleArray);
                $sheet->getStyle('A3')->applyFromArray($styleArray);
                $sheet->getStyle('A4')->applyFromArray($styleArray);
                $sheet->getStyle('A6')->applyFromArray($styleArray);
                $sheet->getStyle('B6')->applyFromArray($styleArray);
                $sheet->getStyle('C6')->applyFromArray($styleArray);
                $sheet->getStyle('D6')->applyFromArray($styleArray);
                $sheet->getStyle('E6')->applyFromArray($styleArray);

                $sheet->getColumnDimension('A')->setWidth(200, 'px');
                $sheet->getColumnDimension('B')->setWidth(200, 'px');
                $sheet->getColumnDimension('C')->setWidth(200, 'px');
                $sheet->getColumnDimension('D')->setWidth(200, 'px');
                $sheet->getColumnDimension('E')->setWidth(200, 'px');

                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', $cooperativeName);
                $sheet->setCellValue('A2', 'Trial Balance');
                $sheet->setCellValue('A3', 'Start Date:');
                $sheet->setCellValue('B3', (new DateTime($startDate))->format('Y-m-d'));
                $sheet->setCellValue('A4', 'End Date');
                $sheet->setCellValue('B4', (new DateTime($endDate))->format('Y-m-d'));

                $idx = 6;

                if ($hasCompare) {
                    $sheet->mergeCells('B'.$idx.':C'.$idx);
                    $sheet->mergeCells('D'.$idx.':E'.$idx);
                    $sheet->getStyle('B'.$idx.':C'.$idx)->applyFromArray($styleArray);
                    $sheet->getStyle('D'.$idx.':E'.$idx)->applyFromArray($styleArray);
                    $sheet->getStyle('B'.$idx.':C'.$idx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('D'.$idx.':E'.$idx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $sheet->setCellValue('B'.$idx, date('Y', strtotime($startDate)));
                    $sheet->setCellValue('D'.$idx, date('Y', strtotime($compareStartDate)));
                    $idx += 1;
                }

                $sheet->getStyle('B'.$idx.':E'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'ACCOUNT');
                $sheet->setCellValue('B'.$idx, 'DEBIT');
                $sheet->setCellValue('C'.$idx, 'CREDIT');
                if ($hasCompare) {
                    $sheet->setCellValue('D'.$idx, 'DEBIT');
                    $sheet->setCellValue('E'.$idx, 'CREDIT');
                }
                $idx += 1;

                $sheet->getStyle('B'.$idx.':B100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('C'.$idx.':C100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('D'.$idx.':D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('E'.$idx.':E100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // assets
                $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'ASSETS');
                $idx += 1;
                foreach ($records['assets'] as $x => $record) {
                    $sheet->setCellValue('A'.$idx, $record->name);
                    $sheet->setCellValue('B'.$idx, number_format($record->debit, 2));
                    $sheet->setCellValue('C'.$idx, '-');

                    if ($hasCompare) {
                        $debit = count($compare_records['assets']) ? number_format($compare_records['assets'][$x]->debit, 2) : '0.00';
                        $sheet->setCellValue('D'.$idx, number_format($debit, 2));
                        $sheet->setCellValue('E'.$idx, '-');

                        $compareDebitSum += count($compare_records['assets']) ? $compare_records['assets'][$x]->debit : 0;
                    }

                    $debitSum += $record->debit;
                    $idx += 1;
                }

                // expenses
                $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'EXPENSES');
                $idx += 1;
                foreach ($records['expenses'] as $x => $record) {
                    $sheet->setCellValue('A'.$idx, $record->name);
                    $sheet->setCellValue('B'.$idx, number_format($record->debit, 2));
                    $sheet->setCellValue('C'.$idx, '-');

                    if ($hasCompare) {
                        $debit = count($compare_records['expenses']) ? number_format($compare_records['expenses'][$x]->debit, 2) : '0.00';
                        $sheet->setCellValue('D'.$idx, number_format($debit, 2));
                        $sheet->setCellValue('E'.$idx, '-');

                        $compareDebitSum += count($compare_records['expenses']) ? $compare_records['assets'][$x]->debit : 0;
                    }

                    $debitSum += $record->debit;
                    $idx += 1;
                }

                // liabilities
                $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'LIABILITIES');
                $idx += 1;
                foreach ($records['liabilities'] as $record) {
                    $sheet->setCellValue('A'.$idx, $record->name);
                    $sheet->setCellValue('B'.$idx, '-');
                    $sheet->setCellValue('C'.$idx, number_format($record->credit, 2));

                    if ($hasCompare) {
                        $credit = count($compare_records['liabilities']) ? number_format($compare_records['liabilities'][$x]->credit, 2) : '0.00';
                        $sheet->setCellValue('D'.$idx, '-');
                        $sheet->setCellValue('E'.$idx, number_format($credit, 2));

                        $compareCreditSum += count($compare_records['liabilities']) ? number_format($compare_records['liabilities'][$x]->credit, 2) : '0.00';
                    }

                    $creditSum += $record->credit;
                    $idx += 1;
                }

                // revenue
                $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'REVENUE');
                $idx += 1;
                foreach ($records['revenue'] as $record) {
                    $sheet->setCellValue('A'.$idx, $record->name);
                    $sheet->setCellValue('B'.$idx, '-');
                    $sheet->setCellValue('C'.$idx, number_format($record->credit, 2));

                    if ($hasCompare) {
                        $credit = count($compare_records['revenue']) ? number_format($compare_records['revenue'][$x]->credit, 2) : '0.00';
                        $sheet->setCellValue('D'.$idx, '-');
                        $sheet->setCellValue('E'.$idx, number_format($credit, 2));

                        $compareCreditSum += count($compare_records['revenue']) ? number_format($compare_records['revenue'][$x]->credit, 2) : '0.00';
                    }

                    $creditSum += $record->credit;
                    $idx += 1;
                }

                // totals
                $sheet->getStyle('B'.$idx.':E'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('B'.$idx, number_format($debitSum, 2));
                $sheet->setCellValue('C'.$idx, number_format($creditSum, 2));

                if ($hasCompare) {
                    $sheet->setCellValue('D'.$idx, number_format($compareDebitSum, 2));
                    $sheet->setCellValue('E'.$idx, number_format($compareCreditSum, 2));
                }

                $writer = new Xlsx($excel);
                $filename = rand();
                $created = Storage::disk('public')->put($filename, '');
                if ($created) {

                    $filepath = "../storage/app/public/$filename";
                    $writer->save($filepath);
            
                    return response()->download($filepath, 'TrialBalance')->deleteFileAfterSend(true);

                } else {
                    throw new Exception("Failed! The report could not be generated", 1);
                }
            }  

        } catch (\Throwable $th) {
            Log::info($th->getFile().' '.$th->getLine().' '.$th->getMessage());
            toastr()->error('Failed! Do you have Data for that Period?');
            return back();
        }
    }

    //balance sheet
    public function balanceSheet($period_id, Request $request)
    {
        $user = Auth::user();

        try {

            $download = $request->query('download', 'pdf');

            $filter_ranges = get_financial_period_statement_ranges($period_id,$request->from, $request->to);
            $period = $filter_ranges["period"];
            $filter_data = $filter_ranges["filter_data"];
            $balance_bf = $filter_ranges["balance_bf"];
            $balance_cf = $filter_ranges["balance_cf"];

            $coopid = $user->cooperative_id;
            $startDate = $filter_data['from'];
            $endDate = $filter_data['to'];

            $compareStartDate = '';
            $compareEndDate = '';

            $records = [
                'current_assets' => $this->getLedgerBalances('Assets', $startDate, $endDate, $coopid, 'current'),
                'long_term_assets' => $this->getLedgerBalances('Assets', $startDate, $endDate, $coopid, 'long term'),
                'current_liabilities' => $this->getLedgerBalances('Liabilities', $startDate, $endDate, $coopid, 'current'),
                'long_term_liabilities' => $this->getLedgerBalances('Liabilities', $startDate, $endDate, $coopid, 'long term'),
                'equity' => $this->getLedgerBalances('Equity', $startDate, $endDate, $coopid)
            ];

            $compare_records = [];
            $compare = filter_var($request->input('compare'), FILTER_VALIDATE_INT);
            if (is_int($compare)) {
                $stArr = explode('-', $startDate);
                $edArr = explode('-', $endDate);
                $compareStartDate = sprintf('%d-%d-%d', $compare, $stArr[1], $stArr[2]);
                $compareEndDate = sprintf('%d-%d-%d', $compare, $edArr[1], $edArr[2]);

                $compare_records = [
                    'current_assets' => $this->getLedgerBalances('Assets', $compareStartDate, $compareEndDate, $coopid, 'current'),
                    'long_term_assets' => $this->getLedgerBalances('Assets', $compareStartDate, $compareEndDate, $coopid, 'long term'),
                    'current_liabilities' => $this->getLedgerBalances('Liabilities', $compareStartDate, $compareEndDate, $coopid, 'current'),
                    'long_term_liabilities' => $this->getLedgerBalances('Liabilities', $compareStartDate, $compareEndDate, $coopid, 'long term'),
                    'equity' => $this->getLedgerBalances('Equity', $compareStartDate, $compareEndDate, $coopid),
                ];
            }

            if ($download == 'pdf') {

                $pdf = app('dompdf.wrapper');
                $pdf->setPaper('a4');
                $pdf->loadView('pdfs.reports.balance_sheet', compact('startDate', 'endDate', 'compareStartDate', 'compareEndDate', 'records', 'compare_records'));

                return $pdf->download('BalanceSheet.pdf');

            }

            if ($download == 'excel') {

                $cooperativeName = $user->cooperative->name;
                $assetsSum = 0; 
                $liabilitiesSum = 0;
                $compareAssetsSum = 0;
                $compareLiabilitiesSum = 0;
                $hasCompare = count($compare_records) > 0 ? true : false;

                $excel = new Spreadsheet();
                $excel->getProperties()
                    ->setCreator($cooperativeName)
                    ->setTitle('BalanceSheet');

                $sheet = $excel->getActiveSheet()->setTitle('Balance Sheet');

                $styleArray = array('font' => array('bold' => true));

                $sheet->getStyle('A1')->applyFromArray($styleArray);
                $sheet->getStyle('A2')->applyFromArray($styleArray);
                $sheet->getStyle('A3')->applyFromArray($styleArray);
                $sheet->getStyle('A4')->applyFromArray($styleArray);
                $sheet->getStyle('A6')->applyFromArray($styleArray);
                $sheet->getStyle('B6')->applyFromArray($styleArray);
                $sheet->getStyle('C6')->applyFromArray($styleArray);

                $sheet->getColumnDimension('A')->setWidth(200, 'px');
                $sheet->getColumnDimension('B')->setWidth(200, 'px');
                if ($hasCompare) {
                    $sheet->getColumnDimension('C')->setWidth(200, 'px');
                }

                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', $cooperativeName);
                $sheet->setCellValue('A2', 'Balance Sheet');
                $sheet->setCellValue('A3', 'Start Date:');
                $sheet->setCellValue('B3', (new DateTime($startDate))->format('Y-m-d'));
                $sheet->setCellValue('A4', 'End Date');
                $sheet->setCellValue('B4', (new DateTime($endDate))->format('Y-m-d'));

                $idx = 6;

                if ($hasCompare) {
                    $sheet->getStyle('B'.$idx)->applyFromArray($styleArray);
                    $sheet->getStyle('C'.$idx)->applyFromArray($styleArray);

                    $sheet->setCellValue('B'.$idx, date('Y', strtotime($startDate)));
                    $sheet->setCellValue('C'.$idx, date('Y', strtotime($compareStartDate)));
                    $idx += 1;
                }

                $sheet->setCellValue('A'.$idx, 'ASSETS');

                $sheet->getStyle('B'.$idx.':B100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('C'.$idx.':C100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // current assets
                $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'CURRENT ASSETS');
                $idx += 1;
                foreach ($records['current_assets'] as $x => $record) {
                    $sheet->setCellValue('A'.$idx, $record->name);
                    $sheet->setCellValue('B'.$idx, number_format($record->debit, 2));
                   
                    if ($hasCompare) {
                        $debit = count($compare_records['current_assets']) ? $compare_records['current_assets'][$x]->debit : 0; 
                        $sheet->setCellValue('C'.$idx, number_format($debit, 2));

                        $compareAssetsSum += count($compare_records['current_assets']) ? $compare_records['current_assets'][$x]->debit : 0;
                    }

                    $assetsSum += $record->debit;
                    $idx += 1;
                }

                // long term assets
                $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'LONG TERM ASSETS');
                $idx += 1;
                foreach ($records['long_term_assets'] as $record) {
                    $sheet->setCellValue('A'.$idx, $record->name);
                    $sheet->setCellValue('B'.$idx, number_format($record->debit, 2));

                    if ($hasCompare) {
                        $debit = count($compare_records['long_term_assets']) ? $compare_records['long_term_assets'][$x]->debit : 0; 
                        $sheet->setCellValue('C'.$idx, number_format($debit, 2));

                        $compareAssetsSum += count($compare_records['long_term_assets']) ? $compare_records['long_term_assets'][$x]->debit : 0;
                    }

                    $assetsSum += $record->debit;
                    $idx += 1;
                }

                // total assets
                $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'TOTAL ASSETS');
                $sheet->setCellValue('B'.$idx, number_format($assetsSum, 2));
                if ($hasCompare) {
                    $sheet->setCellValue('C'.$idx, number_format($compareAssetsSum, 2));
                }
                $idx += 2;

                // liabilities + equity
                $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'LIABILITIES + EQUITY');
                $idx += 1;

                // equity
                $equity = count($records['equity']) > 0 ? $records['equity'][0]->credit : 0;
                $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'EQUITY');
                $sheet->setCellValue('B'.$idx, number_format($equity, 2));
                if ($hasCompare) {
                    $equity = count($compare_records['equity']) > 0 ? $compare_records['equity'][0]->credit : 0;
                    $sheet->setCellValue('C'.$idx, number_format($equity, 2));
                }
                $idx += 1;

                $liabilitiesSum += $equity; 
                
                // current liabilities
                $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'CURRENT LIABILITIES');
                $idx += 1;
                foreach ($records['current_liabilities'] as $x => $record) {
                    $sheet->setCellValue('A'.$idx, $record->name);
                    $sheet->setCellValue('B'.$idx, number_format($record->credit, 2));
                    
                    if ($hasCompare) {
                        $credit = count($compare_records['current_liabilities']) > 0 ? $compare_records['current_liabilities'][$x]->credit : 0; 
                        $sheet->setCellValue('C'.$idx, number_format($credit, 2));

                        $compareLiabilitiesSum += $credit;
                    }

                    $liabilitiesSum += $record->credit;
                    $idx += 1;
                }

                // long term liabilities
                $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'LONG TERM LIABILITIES');
                $idx += 1;
                foreach ($records['long_term_liabilities'] as $x => $record) {
                    $sheet->setCellValue('A'.$idx, $record->name);
                    $sheet->setCellValue('B'.$idx, number_format($record->credit, 2));

                    if ($hasCompare) {
                        $credit = count($compare_records['long_term_liabilities']) > 0 ? $compare_records['long_term_liabilities'][$x]->credit : 0; 
                        $sheet->setCellValue('C'.$idx, number_format($credit, 2));
                        
                        $compareLiabilitiesSum += $credit;
                    }

                    $liabilitiesSum += $record->credit;
                    $idx += 1;
                }

                // total liabilities
                $sheet->getStyle('A'.$idx.':C'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'TOTAL LIABILITIES');
                $sheet->setCellValue('B'.$idx, number_format($liabilitiesSum, 2));
                if ($hasCompare) {
                    $sheet->setCellValue('C'.$idx, number_format($compareLiabilitiesSum, 2));
                }

                $writer = new Xlsx($excel);
                $filename = rand();
                $created = Storage::disk('public')->put($filename, '');
                if ($created) {

                    $filepath = "../storage/app/public/$filename";
                    $writer->save($filepath);
            
                    return response()->download($filepath, 'BalanceSheet')->deleteFileAfterSend(true);

                } else {
                    throw new Exception("Failed! The report could not be generated", 1);
                }
            }

        } catch (\Throwable $th) {
            Log::info($th->getFile().' '.$th->getLine().' '.$th->getMessage());
            toastr()->error('Failed! Do you have Data for that Period?');
            return back();
        }
    }

    // Income Statement
    public function incomeStatement(Request $request, $period_id)
    {
        try {

            $download = $request->query('download', 'pdf');

            $user = Auth::user();

            $filter_ranges = get_financial_period_statement_ranges($period_id,$request->from, $request->to);
            $period = $filter_ranges["period"];
            $filter_data = $filter_ranges["filter_data"];
            $balance_bf = $filter_ranges["balance_bf"];
            $balance_cf = $filter_ranges["balance_cf"];

            $coopid = $user->cooperative_id;
            $startDate = $filter_data['from'];
            $endDate = $filter_data['to'];

            $compareStartDate = '';
            $compareEndDate = '';

            $records = [
                'revenue'  => $this->getLedgerBalances('Revenue', $startDate, $endDate, $coopid),
                'expenses' => $this->getLedgerBalances('Expenses', $startDate, $endDate, $coopid)
            ];

            $compare_records = [];
            $compare = filter_var($request->input('compare'), FILTER_VALIDATE_INT);
            if (is_int($compare)) {
                $stArr = explode('-', $startDate);
                $edArr = explode('-', $endDate);
                $compareStartDate = sprintf('%d-%d-%d', $compare, $stArr[1], $stArr[2]);
                $compareEndDate = sprintf('%d-%d-%d', $compare, $edArr[1], $edArr[2]);

                $compare_records = [
                    'revenue'  => $this->getLedgerBalances('Revenue', $compareStartDate, $compareEndDate, $coopid),
                    'expenses' => $this->getLedgerBalances('Expenses', $compareStartDate, $compareEndDate, $coopid),
                ];
            }

            if ($download == 'pdf') {

                $pdf = app('dompdf.wrapper');
                $pdf->setPaper('a4');
                $pdf->loadView('pdfs.reports.income_statement', compact('startDate', 'endDate', 'compareStartDate', 'compareEndDate', 'records', 'compare_records'));

                return $pdf->download('IncomeStatement.pdf');

            }

            if ($download == 'excel') {

                $cooperativeName = $user->cooperative->name;
                $revenueSum = 0;
                $expensesSum = 0;
                $compareRevenueSum = 0;
                $compareExpensesSum = 0;
                $hasCompare = count($compare_records) > 0 ? true : false;

                $excel = new Spreadsheet();
                $excel->getProperties()
                    ->setCreator($cooperativeName)
                    ->setTitle('IncomeStatement');

                $sheet = $excel->getActiveSheet()->setTitle('Income Statement');

                $styleArray = array('font' => array('bold' => true));

                $sheet->getStyle('A1')->applyFromArray($styleArray);
                $sheet->getStyle('A2')->applyFromArray($styleArray);
                $sheet->getStyle('A3')->applyFromArray($styleArray);
                $sheet->getStyle('A4')->applyFromArray($styleArray);
                $sheet->getStyle('A6')->applyFromArray($styleArray);
                $sheet->getStyle('B6')->applyFromArray($styleArray);
                $sheet->getStyle('C6')->applyFromArray($styleArray);

                $sheet->getColumnDimension('A')->setWidth(200, 'px');
                $sheet->getColumnDimension('B')->setWidth(200, 'px');
                if ($hasCompare) {
                    $sheet->getColumnDimension('C')->setWidth(200, 'px');
                }

                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', $cooperativeName);
                $sheet->setCellValue('A2', 'Balance Sheet');
                $sheet->setCellValue('A3', 'Start Date:');
                $sheet->setCellValue('B3', (new DateTime($startDate))->format('Y-m-d'));
                $sheet->setCellValue('A4', 'End Date');
                $sheet->setCellValue('B4', (new DateTime($endDate))->format('Y-m-d'));

                $idx = 6;

                if ($hasCompare) {
                    $sheet->getStyle('B'.$idx)->applyFromArray($styleArray);
                    $sheet->getStyle('C'.$idx)->applyFromArray($styleArray);

                    $sheet->setCellValue('B'.$idx, date('Y', strtotime($startDate)));
                    $sheet->setCellValue('C'.$idx, date('Y', strtotime($compareStartDate)));
                    $idx += 1;
                }

                $sheet->setCellValue('A'.$idx, 'REVENUE');

                $sheet->getStyle('B'.$idx.':B100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('C'.$idx.':C100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // revenue
                $idx += 1;
                foreach ($records['revenue'] as $x => $record) {
                    $sheet->setCellValue('A'.$idx, $record->name);
                    $sheet->setCellValue('B'.$idx, number_format($record->credit, 2));
                    
                    if ($hasCompare) {
                        $credit = count($compare_records['revenue']) ? $compare_records['revenue'][$x]->credit : 0;
                        $sheet->setCellValue('C'.$idx, number_format($credit, 2));

                        $compareRevenueSum += count($compare_records['revenue']) ? $compare_records['revenue'][$x]->credit : 0;
                    }

                    $revenueSum += $record->credit;
                    $idx += 1;
                }

                // total revenue
                $sheet->getStyle('A'.$idx.':C'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'TOTAL REVENUE');
                $sheet->setCellValue('B'.$idx, number_format($revenueSum, 2));
                if ($hasCompare) {
                    $sheet->setCellValue('C'.$idx, number_format($compareRevenueSum, 2));
                }
                $idx += 1;

                // expenses
                foreach ($records['expenses'] as $x => $record) {
                    $sheet->setCellValue('A'.$idx, $record->name);
                    $sheet->setCellValue('B'.$idx, number_format($record->debit, 2));

                    if ($hasCompare) {
                        $debit = count($compare_records['expenses']) ? $compare_records['expenses'][$x]->debit : 0;
                        $sheet->setCellValue('C'.$idx, number_format($debit, 2));

                        $compareExpensesSum += count($compare_records['expenses']) ? $compare_records['expenses'][$x]->debit : 0;
                    }

                    $expensesSum += $record->debit;
                    $idx += 1;
                }

                // total revenue
                $sheet->getStyle('A'.$idx.':C'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'TOTAL EXPENSES');
                $sheet->setCellValue('B'.$idx, number_format($expensesSum, 2));
                if ($hasCompare) {
                    $sheet->setCellValue('C'.$idx, number_format($compareExpensesSum, 2));
                }
                $idx += 1;

                // net income
                $sheet->getStyle('A'.$idx.':C'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'NET INCOME');
                $sheet->setCellValue('B'.$idx, number_format(($revenueSum - $expensesSum), 2));
                if ($hasCompare) {
                    $sheet->setCellValue('C'.$idx, number_format(($compareRevenueSum - $compareExpensesSum), 2));
                }

                $writer = new Xlsx($excel);
                $filename = rand();
                $created = Storage::disk('public')->put($filename, '');
                if ($created) {

                    $filepath = "../storage/app/public/$filename";
                    $writer->save($filepath);
            
                    return response()->download($filepath, 'IncomeStatement')->deleteFileAfterSend(true);

                } else {
                    throw new Exception("Failed! The report could not be generated", 1);
                }
            }

        } catch (Throwable $th) {
            Log::info($th->getFile().' '.$th->getLine().' '.$th->getMessage());
            toastr()->error('Failed! Do you have Data for that Period?');
            return back();
        }
    }

    // Budget VS Actual
    public function getBudgetVsActual(Request $request, $period_id)
    {
        try {

            $download = $request->query('download', 'pdf');

            $user = Auth::user();

            $filter_ranges = get_financial_period_statement_ranges($period_id,$request->from, $request->to);
            $period = $filter_ranges["period"];
            $filter_data = $filter_ranges["filter_data"];
            $balance_bf = $filter_ranges["balance_bf"];
            $balance_cf = $filter_ranges["balance_cf"];

            $coopid = $user->cooperative_id;
            $startDate = $filter_data['from'];
            $endDate = $filter_data['to'];

            $year = date('Y', strtotime($startDate));

            $records = [];
            $revenue  = $this->getLedgerBalances('Revenue', $startDate, $endDate, $coopid);
            $expenses = $this->getLedgerBalances('Expenses', $startDate, $endDate, $coopid);

            $budget = Budget::where('type', 'YEARLY')->where('year', $year)->where('cooperative_id',  $coopid)->first();

            foreach ($revenue as $rev) {

                $budgetAmount = $budget ?
                    BudgetAmount::where('budget_id', $budget->id)->where('ledger_id', $rev->ledger_id)->first() : 
                    null;

                $overBudget = $budgetAmount ? ($rev->credit > $budgetAmount->amount ? ($rev->credit - $budgetAmount->amount) : 0) : 0; 

                $overBudgetPercent = $overBudget > 0 ? (($overBudget / $budgetAmount->amount) * 100) : 0;

                $records['revenue'][] = [
                    'id' => $rev->ledger_id,
                    'name' => $rev->name,
                    'actual' => $rev->credit,
                    'budget' => $budgetAmount ? $budgetAmount->amount : 0,
                    'over_budget' => $overBudget,
                    'over_budget_percent' =>  number_format($overBudgetPercent)
                ];
            }

            foreach ($expenses as $exp) {

                $budgetAmount = $budget ?
                    BudgetAmount::where('budget_id', $budget->id)->where('ledger_id', $exp->ledger_id)->first() : 
                    null;

                $overBudget = $budgetAmount ? ($exp->debit > $budgetAmount->amount ? ($exp->debit - $budgetAmount->amount) : 0) : 0; 

                $overBudgetPercent = $overBudget > 0 ? (($overBudget / $budgetAmount->amount) * 100) : 0;

                $records['expenses'][] = [
                    'id' => $exp->ledger_id,
                    'name' => $exp->name,
                    'actual' => $exp->debit,
                    'budget' => $budgetAmount ? $budgetAmount->amount : 0,
                    'over_budget' => $overBudget,
                    'over_budget_percent' => number_format($overBudgetPercent)
                ];
            }

            if ($download == 'pdf') {
            
                $pdf = app('dompdf.wrapper');
                $pdf->setPaper('a4');
                $pdf->loadView('pdfs.reports.budget_vs_actual', compact('startDate', 'endDate', 'records'));

                return $pdf->download('BudgetVsActual.pdf');
            }

            if ($download == 'excel') {

                $cooperativeName = $user->cooperative->name;
                $revenueSum = 0;
                $revenueBudgetSum = 0;
                $revenueOverBudgetSum = 0;
                $expensesSum = 0;
                $expensesBudgetSum = 0;
                $expensesOverBudgetSum = 0;

                $excel = new Spreadsheet();
                $excel->getProperties()
                    ->setCreator($cooperativeName)
                    ->setTitle('BudgetsVsActual');

                $sheet = $excel->getActiveSheet()->setTitle('Budget Vs Actual');

                $styleArray = array('font' => array('bold' => true));

                $sheet->getStyle('A1')->applyFromArray($styleArray);
                $sheet->getStyle('A2')->applyFromArray($styleArray);
                $sheet->getStyle('A3')->applyFromArray($styleArray);
                $sheet->getStyle('A4')->applyFromArray($styleArray);
                $sheet->getStyle('A6')->applyFromArray($styleArray);
                $sheet->getStyle('B6')->applyFromArray($styleArray);
                $sheet->getStyle('C6')->applyFromArray($styleArray);
                $sheet->getStyle('D6')->applyFromArray($styleArray);
                $sheet->getStyle('E6')->applyFromArray($styleArray);
                $sheet->getStyle('A7')->applyFromArray($styleArray);

                $sheet->getColumnDimension('A')->setWidth(200, 'px');
                $sheet->getColumnDimension('B')->setWidth(200, 'px');
                $sheet->getColumnDimension('C')->setWidth(200, 'px');
                $sheet->getColumnDimension('D')->setWidth(200, 'px');
                $sheet->getColumnDimension('E')->setWidth(200, 'px');

                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', $cooperativeName);
                $sheet->setCellValue('A2', 'Budget Vs Actual');
                $sheet->setCellValue('A3', 'Start Date:');
                $sheet->setCellValue('B3', (new DateTime($startDate))->format('Y-m-d'));
                $sheet->setCellValue('A4', 'End Date');
                $sheet->setCellValue('B4', (new DateTime($endDate))->format('Y-m-d'));

                $sheet->setCellValue('A6', '');
                $sheet->setCellValue('B6', 'ACTUAL');
                $sheet->setCellValue('C6', 'BUDGET');
                $sheet->setCellValue('D6', 'OVER BUDGET');
                $sheet->setCellValue('E6', '% BUDGET');

                $sheet->setCellValue('A7', 'REVENUE');

                $sheet->getStyle('B8:B100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('C8:C100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('D8:C100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('E8:C100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            
                // revenue
                $idx = 8;
                foreach($records['revenue'] as $record) {
                    $sheet->setCellValue('A'.$idx, $record['name']);
                    $sheet->setCellValue('B'.$idx, number_format($record['actual'], 2));
                    $sheet->setCellValue('C'.$idx, number_format($record['budget'], 2));
                    $sheet->setCellValue('D'.$idx, number_format($record['over_budget'], 2));
                    $sheet->setCellValue('E'.$idx, $record['over_budget_percent']);

                    $revenueSum += $record['actual'];
                    $revenueBudgetSum += $record['budget'];
                    $revenueOverBudgetSum += $record['over_budget'];

                    $idx += 1;
                }

                // total revenue
                $sheet->getStyle('A'.$idx.':E'.$idx)->applyFromArray($styleArray);
                $percent = $revenueOverBudgetSum > 0 ? (($revenueOverBudgetSum / $revenueBudgetSum) * 100) : 0;
                $sheet->setCellValue('A'.$idx, 'TOTAL REVENUE');
                $sheet->setCellValue('B'.$idx, number_format($revenueSum, 2));
                $sheet->setCellValue('C'.$idx, number_format($revenueBudgetSum, 2));
                $sheet->setCellValue('D'.$idx, number_format($revenueOverBudgetSum, 2));
                $sheet->setCellValue('E'.$idx, $percent);
                $idx += 1;

                // expenses
                foreach($records['expenses'] as $record) {
                    $sheet->setCellValue('A'.$idx, $record['name']);
                    $sheet->setCellValue('B'.$idx, number_format($record['actual'], 2));
                    $sheet->setCellValue('C'.$idx, number_format($record['budget'], 2));
                    $sheet->setCellValue('D'.$idx, number_format($record['over_budget'], 2));
                    $sheet->setCellValue('E'.$idx, $record['over_budget_percent']);

                    $expensesSum += $record['actual'];
                    $expensesBudgetSum += $record['budget'];
                    $expensesOverBudgetSum += $record['over_budget'];

                    $idx += 1;
                }

                // total expenses
                $sheet->getStyle('A'.$idx.':E'.$idx)->applyFromArray($styleArray);
                $percent = $expensesOverBudgetSum > 0 ? (($expensesOverBudgetSum / $expensesBudgetSum) * 100) : 0;
                $sheet->setCellValue('A'.$idx, 'TOTAL EXPENSES');
                $sheet->setCellValue('B'.$idx, number_format($expensesSum, 2));
                $sheet->setCellValue('C'.$idx, number_format($expensesBudgetSum, 2));
                $sheet->setCellValue('D'.$idx, number_format($expensesOverBudgetSum, 2));
                $sheet->setCellValue('E'.$idx, $percent);
                $idx += 1;

                // net income
                $sheet->getStyle('A'.$idx.':B'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'NET INCOME');
                $sheet->setCellValue('B'.$idx, number_format(($revenueSum - $expensesSum), 2));

                $writer = new Xlsx($excel);
                $filename = rand();
                $created = Storage::disk('public')->put($filename, '');
                if ($created) {

                    $filepath = "../storage/app/public/$filename";
                    $writer->save($filepath);
            
                    return response()->download($filepath, 'BudgetVsActual')->deleteFileAfterSend(true);

                } else {
                    throw new Exception("Failed! The report could not be generated", 1);
                }
            }

        } catch (Throwable $th) {
            Log::error($th);
            toastr()->error('Failed! Do you have Data for that Period?');
            return back();
        }
    }

    // Account Payables
    public function getAccountPayablesSummary(Request $request, $period_id)
    {
        try {

            $download = $request->query('download', 'pdf');

            $user = Auth::user();

            $filter_ranges = get_financial_period_statement_ranges($period_id,$request->from, $request->to);
            $period = $filter_ranges["period"];
            $filter_data = $filter_ranges["filter_data"];
            $balance_bf = $filter_ranges["balance_bf"];
            $balance_cf = $filter_ranges["balance_cf"];

            $coopid = $user->cooperative_id;
            $startDate = $filter_data['from'];
            $endDate = $filter_data['to'];

            $records = DB::select("SELECT 
                    (
                        SUM(COALESCE(accounting_transactions.credit, 0)) - 
                        SUM(COALESCE(accounting_transactions.debit, 0)) 
                    ) AS amount, 
                    accounting_ledgers.name AS ledger
                FROM 
                    accounting_transactions
                    JOIN accounting_ledgers ON accounting_ledgers.id = accounting_transactions.accounting_ledger_id
                WHERE
                    accounting_ledgers.classification = 'ACCOUNT_PAYABLES' 
                    AND accounting_transactions.cooperative_id = ? 
                    AND accounting_transactions.date BETWEEN ? AND ? 
                GROUP BY 
                    accounting_ledgers.name",
                [ $coopid, $startDate, $endDate ]);

            if ($download == 'pdf') {
                $pdf = app('dompdf.wrapper');
                $pdf->setPaper('a4');
                $pdf->loadView('pdfs.reports.account_payables_summary', compact('startDate', 'endDate', 'records'));

                return $pdf->download('AccountPayablesSummary.pdf');
            }

            if ($download == 'excel') {

                $cooperativeName = $user->cooperative->name;
                $sum = 0;

                $excel = new Spreadsheet();
                $excel->getProperties()
                    ->setCreator($cooperativeName)
                    ->setTitle('AccountPayablesSummary');

                $sheet = $excel->getActiveSheet()->setTitle('Account Payables Summary');

                $styleArray = array('font' => array('bold' => true));

                $sheet->getStyle('A1')->applyFromArray($styleArray);
                $sheet->getStyle('A2')->applyFromArray($styleArray);
                $sheet->getStyle('A3')->applyFromArray($styleArray);
                $sheet->getStyle('A4')->applyFromArray($styleArray);
                $sheet->getStyle('A6')->applyFromArray($styleArray);
                $sheet->getStyle('B6')->applyFromArray($styleArray);

                $sheet->getColumnDimension('A')->setWidth(200, 'px');
                $sheet->getColumnDimension('B')->setWidth(200, 'px');

                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', $cooperativeName);
                $sheet->setCellValue('A2', 'Account Payables Summary');
                $sheet->setCellValue('A3', 'Start Date:');
                $sheet->setCellValue('B3', (new DateTime($startDate))->format('Y-m-d'));
                $sheet->setCellValue('A4', 'End Date');
                $sheet->setCellValue('B4', (new DateTime($endDate))->format('Y-m-d'));

                $sheet->setCellValue('A6', 'ACCOUNT PAYABLES');

                $idx = 7;
                foreach ($records as $record) {
                    $sheet->setCellValue('A'.$idx, $record->ledger);
                    $sheet->setCellValue('B'.$idx, number_format($record->amount, 2));

                    $sum += $record->amount; 
                    $idx += 1;
                }

                // totals
                $sheet->getStyle('A'.$idx.':B'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'TOTAL ACCOUNT PAYABLES');
                $sheet->setCellValue('B'.$idx, number_format($sum, 2));

                $writer = new Xlsx($excel);
                $filename = rand();
                $created = Storage::disk('public')->put($filename, '');
                if ($created) {

                    $filepath = "../storage/app/public/$filename";
                    $writer->save($filepath);
            
                    return response()->download($filepath, 'AccountPayablesSummary')->deleteFileAfterSend(true);

                } else {
                    throw new Exception("Failed! The report could not be generated", 1);
                }
            }

        } catch (Throwable $th) {
            Log::error($th);
            toastr()->error('Failed! Do you have Data for that Period?');
            return back();
        }
    }

    // Account Receivables
    public function getAccountReceivablesSummary(Request $request, $period_id)
    {
        try {

            $download = $request->query('download', 'pdf');

            $user = Auth::user();

            $filter_ranges = get_financial_period_statement_ranges($period_id,$request->from, $request->to);
            $period = $filter_ranges["period"];
            $filter_data = $filter_ranges["filter_data"];
            $balance_bf = $filter_ranges["balance_bf"];
            $balance_cf = $filter_ranges["balance_cf"];

            $coopid = $user->cooperative_id;
            $startDate = $filter_data['from'];
            $endDate = $filter_data['to'];

            $records = DB::select("SELECT 
                    (
                        SUM(COALESCE(accounting_transactions.debit, 0)) - 
                        SUM(COALESCE(accounting_transactions.credit, 0)) 
                    ) AS amount, 
                    accounting_ledgers.name AS ledger
                FROM 
                    accounting_transactions
                    JOIN accounting_ledgers ON accounting_ledgers.id = accounting_transactions.accounting_ledger_id
                WHERE
                    accounting_ledgers.classification = 'ACCOUNT_RECEIVABLES' 
                    AND accounting_transactions.cooperative_id = ? 
                    AND accounting_transactions.date BETWEEN ? AND ? 
                GROUP BY 
                    accounting_ledgers.name",
                [ $coopid, $startDate, $endDate ]);

            if ($download == 'pdf') {

                $pdf = app('dompdf.wrapper');
                $pdf->setPaper('a4');
                $pdf->loadView('pdfs.reports.account_receivables_summary', compact('startDate', 'endDate', 'records'));

                return $pdf->download('AccountReceivablesSummary.pdf');
            }

            if ($download == 'excel') {

                $cooperativeName = $user->cooperative->name;
                $sum = 0;

                $excel = new Spreadsheet();
                $excel->getProperties()
                    ->setCreator($cooperativeName)
                    ->setTitle('AccountPayablesSummary');

                $sheet = $excel->getActiveSheet()->setTitle('Account Payables Summary');

                $styleArray = array('font' => array('bold' => true));

                $sheet->getStyle('A1')->applyFromArray($styleArray);
                $sheet->getStyle('A2')->applyFromArray($styleArray);
                $sheet->getStyle('A3')->applyFromArray($styleArray);
                $sheet->getStyle('A4')->applyFromArray($styleArray);
                $sheet->getStyle('A6')->applyFromArray($styleArray);
                $sheet->getStyle('B6')->applyFromArray($styleArray);

                $sheet->getColumnDimension('A')->setWidth(200, 'px');
                $sheet->getColumnDimension('B')->setWidth(200, 'px');

                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', $cooperativeName);
                $sheet->setCellValue('A2', 'Account Receivables Summary');
                $sheet->setCellValue('A3', 'Start Date:');
                $sheet->setCellValue('B3', (new DateTime($startDate))->format('Y-m-d'));
                $sheet->setCellValue('A4', 'End Date');
                $sheet->setCellValue('B4', (new DateTime($endDate))->format('Y-m-d'));

                $sheet->setCellValue('A6', 'ACCOUNT RECEIVABLES');

                $idx = 7;
                foreach ($records as $record) {
                    $sheet->setCellValue('A'.$idx, $record->ledger);
                    $sheet->setCellValue('B'.$idx, number_format($record->amount, 2));

                    $sum += $record->amount; 
                    $idx += 1;
                }

                // totals
                $sheet->getStyle('A'.$idx.':B'.$idx)->applyFromArray($styleArray);
                $sheet->setCellValue('A'.$idx, 'TOTAL ACCOUNT RECEIVABLES');
                $sheet->setCellValue('B'.$idx, number_format($sum, 2));

                $writer = new Xlsx($excel);
                $filename = rand();
                $created = Storage::disk('public')->put($filename, '');
                if ($created) {

                    $filepath = "../storage/app/public/$filename";
                    $writer->save($filepath);
            
                    return response()->download($filepath, 'AccountReceivablesSummary')->deleteFileAfterSend(true);

                } else {
                    throw new Exception("Failed! The report could not be generated", 1);
                }
            }

        } catch (Throwable $th) {
            Log::error($th);
            toastr()->error('Failed! Do you have Data for that Period?');
            return back();
        }
    }

    public function getFarmerConsolidatedReport(Request $request, $period_id)
    {
        $request->validate([
            'farmer' => 'required'
        ]);
        
        try {

            $download = $request->query('download', 'pdf');
            
            $user = Auth::user();
            $coopid = $user->cooperative_id;

            $farmer = Farmer::select(['farmers.id', DB::raw('CONCAT(users.first_name, " ", users.other_names) AS name') ])
                        ->join('users', 'users.id', '=', 'farmers.user_id')
                        ->where('users.cooperative_id', $coopid)
                        ->where('farmers.id', $request->input('farmer'))
                        ->first();
            $farmer_name = $farmer ? $farmer->name : '---';
            $farmer_id = $farmer ? $farmer->id : null;

            $filter_ranges = get_financial_period_statement_ranges($period_id,$request->from, $request->to);
            $period = $filter_ranges["period"];
            $filter_data = $filter_ranges["filter_data"];
            $balance_bf = $filter_ranges["balance_bf"];
            $balance_cf = $filter_ranges["balance_cf"];

            
            $startDate = $filter_data['from'];
            $endDate = $filter_data['to'];


            $records[] = [
                'label' => 'Total collections from sales:',
                'value' => $this->getFarmerBalances('collections', $startDate, $endDate, $farmer_id),
            ];
            $records[] = [
                'label' => 'Total payments made:',
                'value' => $this->getFarmerBalances('payments_made', $startDate, $endDate, $farmer_id),
            ];
            $records[] = [
                'label' => 'Total outstanding loan balance:',
                'value' => $this->getFarmerBalances('loan_balance', $startDate, $endDate, $farmer_id),
            ];
            $records[] = [
                'label' => 'Total purchases made:',
                'value' => $this->getFarmerBalances('purchases', $startDate, $endDate, $farmer_id),
            ];
            $records[] = [
                'label' => 'Total pending payments:',
                'value' => $this->getFarmerBalances('payments_pending', $startDate, $endDate, $farmer_id),
            ];
            $records[] = [
                'label' => 'Total savings:',
                'value' => $this->getFarmerBalances('savings', $startDate, $endDate, $farmer_id),
            ];

            if ($download == 'pdf') {

                $pdf = app('dompdf.wrapper');
                $pdf->setPaper('a4');
                $pdf->loadView('pdfs.reports.farmer_consolidated_report', compact('startDate', 'endDate', 'records', 'farmer_name'));

                $filename = sprintf('FarmerConsolidatedReport-%s.pdf', str_replace(' ', '_', $farmer_name));
                return $pdf->download($filename);
            }

            if ($download == 'excel') {

                $cooperativeName = $user->cooperative->name;
                $collectionsSum = 0;

                $excel = new Spreadsheet();
                $excel->getProperties()
                    ->setCreator($cooperativeName)
                    ->setTitle('Farmer Consolidated Report');

                $sheet = $excel->getActiveSheet()->setTitle('Farmer Consolidated Report');

                $styleArray = array('font' => array('bold' => true));

                $sheet->getStyle('A1')->applyFromArray($styleArray);
                $sheet->getStyle('A2')->applyFromArray($styleArray);
                $sheet->getStyle('A3')->applyFromArray($styleArray);
                $sheet->getStyle('A4')->applyFromArray($styleArray);
                $sheet->getStyle('A5')->applyFromArray($styleArray);
                $sheet->getStyle('A7')->applyFromArray($styleArray);
                $sheet->getStyle('B7')->applyFromArray($styleArray);

                $sheet->getColumnDimension('A')->setWidth(200, 'px');
                $sheet->getColumnDimension('B')->setWidth(200, 'px');

                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', $cooperativeName);
                $sheet->setCellValue('A2', 'Farmer Consolidated Report');
                $sheet->setCellValue('A3', 'Farmer:');
                $sheet->setCellValue('B3', $farmer_name);
                $sheet->setCellValue('A4', 'Start Date:');
                $sheet->setCellValue('B4', (new DateTime($startDate))->format('Y-m-d'));
                $sheet->setCellValue('A5', 'End Date');
                $sheet->setCellValue('B5', (new DateTime($endDate))->format('Y-m-d'));

                $sheet->setCellValue('A7', 'ITEM');
                $sheet->setCellValue('B7', 'AMOUNT IN KSH');

                $sheet->getStyle('B8:B100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            
                $idx = 8;
                foreach ($records as $record) {
                    if ($record['label'] == 'Total collections from sales:') {
                        $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                        $sheet->setCellValue('A'.$idx, $record['label']);
                        $idx += 1;

                        foreach ($record['value'] as $value) {
                            $collectionsSum += $value->amount;
                            $sheet->setCellValue('A'.$idx, '   '.$value->name);
                            $sheet->setCellValue('B'.$idx, number_format($value->amount, 2));
                            $idx += 1;
                        }

                        $sheet->getStyle('A'.$idx.':B'.$idx)->applyFromArray($styleArray);
                        $sheet->setCellValue('A'.$idx, '   Total collections:');
                        $sheet->setCellValue('B'.$idx, number_format($collectionsSum, 2));
                        $idx += 1;
                    }
                    else {
                        $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                        $sheet->setCellValue('A'.$idx, $record['label']);
                        $sheet->setCellValue('B'.$idx, number_format($record['value'], 2));
                        $idx += 1;
                    }                    
                }

                $writer = new Xlsx($excel);
                $filename = rand();
                $created = Storage::disk('public')->put($filename, '');
                if ($created) {

                    $filepath = "../storage/app/public/$filename";
                    $writer->save($filepath);
            
                    $fname = 'FarmerConsolidatedReport-'.str_replace(' ', '_', $farmer_name);
                    return response()->download($filepath, $fname)->deleteFileAfterSend(true);

                } else {
                    throw new Exception("Failed! The report could not be generated", 1);
                }
            }

        } catch (Throwable $th) {
            Log::error($th);
            toastr()->error('Failed! Do you have Data for that Period?');
            return back();
        }
    }

    public function getCooperativeConsolidatedReport(Request $request, $period_id)
    {
        try {

            $download = $request->query('download', 'pdf');

            $user = Auth::user();
            $coopid = $user->cooperative_id;

            $farmer = Farmer::select(['farmers.id', DB::raw('CONCAT(users.first_name, " ", users.other_names) AS name') ])
                        ->join('users', 'users.id', '=', 'farmers.user_id')
                        ->where('users.cooperative_id', $coopid)
                        ->where('farmers.id', $request->input('farmer'))
                        ->first();
            $farmer_name = $farmer ? $farmer->name : '---';
            $farmer_id = $farmer ? $farmer->id : null;

            $filter_ranges = get_financial_period_statement_ranges($period_id,$request->from, $request->to);
            $period = $filter_ranges["period"];
            $filter_data = $filter_ranges["filter_data"];
            $balance_bf = $filter_ranges["balance_bf"];
            $balance_cf = $filter_ranges["balance_cf"];

            $startDate = $filter_data['from'];
            $endDate = $filter_data['to'];

            $records[] = [
                'label' => 'Total revenue from sales:',
                'value' => $this->getCooperativeBalances('sales', $startDate, $endDate, $coopid),
            ];
            $records[] = [
                'label' => 'Number of farmers registered:',
                'value' => $this->getCooperativeBalances('farmers', $startDate, $endDate, $coopid),
            ];
            $records[] = [
                'label' => 'Total customer payments:',
                'value' => $this->getCooperativeBalances('customer_payments', $startDate, $endDate, $coopid),
            ];
            $records[] = [
                'label' => 'Total customer debts:',
                'value' => $this->getCooperativeBalances('customer_debts', $startDate, $endDate, $coopid),
            ];
            $records[] = [
                'label' => 'Total operating expenses:',
                'value' => $this->getCooperativeBalances('expenses', $startDate, $endDate, $coopid),
            ];
            $records[] = [
                'label' => 'Total supplier payments:',
                'value' => $this->getCooperativeBalances('supplier_payments', $startDate, $endDate, $coopid),
            ];
            $records[] = [
                'label' => 'Total loans issued:',
                'value' => $this->getCooperativeBalances('loans_issued', $startDate, $endDate, $coopid),
            ];
            $records[] = [
                'label' => 'Total loans repaid:',
                'value' => $this->getCooperativeBalances('loans_repaid', $startDate, $endDate, $coopid),
            ];
            $records[] = [
                'label' => 'Total outstanding cooperative loan balance:',
                'value' => $this->getCooperativeBalances('coop_loan', $startDate, $endDate, $coopid),
            ];
            $records[] = [
                'label' => 'Total number of employees:',
                'value' => $this->getCooperativeBalances('employees', $startDate, $endDate, $coopid),
            ];
            $records[] = [
                'label' => 'Total payroll expenses:',
                'value' => $this->getCooperativeBalances('payroll', $startDate, $endDate, $coopid),
            ];
            $records[] = [
                'label' => 'Total inventory value:',
                'value' => $this->getCooperativeBalances('inventory', $startDate, $endDate, $coopid),
            ];

            if ($download == 'pdf') {

                $pdf = app('dompdf.wrapper');
                $pdf->setPaper('a4');
                $pdf->loadView('pdfs.reports.cooperative_consolidated_report', compact('startDate', 'endDate', 'records'));

                return $pdf->download('CooperativeConsolidatedReport.pdf');
            }

            if ($download == 'excel') {

                $cooperativeName = $user->cooperative->name;

                $excel = new Spreadsheet();
                $excel->getProperties()
                    ->setCreator($cooperativeName)
                    ->setTitle('Cooperative Consolidated Report');

                $sheet = $excel->getActiveSheet()->setTitle('Cooperative Consolidated Report');

                $styleArray = array('font' => array('bold' => true));

                $sheet->getStyle('A1')->applyFromArray($styleArray);
                $sheet->getStyle('A2')->applyFromArray($styleArray);
                $sheet->getStyle('A3')->applyFromArray($styleArray);
                $sheet->getStyle('A4')->applyFromArray($styleArray);
                $sheet->getStyle('A6')->applyFromArray($styleArray);
                $sheet->getStyle('B6')->applyFromArray($styleArray);

                $sheet->getColumnDimension('A')->setWidth(200, 'px');
                $sheet->getColumnDimension('B')->setWidth(200, 'px');

                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', $cooperativeName);
                $sheet->setCellValue('A2', 'Cooperative Consolidated Report');
                $sheet->setCellValue('A3', 'Start Date:');
                $sheet->setCellValue('B3', (new DateTime($startDate))->format('Y-m-d'));
                $sheet->setCellValue('A4', 'End Date');
                $sheet->setCellValue('B4', (new DateTime($endDate))->format('Y-m-d'));

                $sheet->setCellValue('A6', 'ITEM');
                $sheet->setCellValue('B6', 'AMOUNT IN KSH');

                $sheet->getStyle('B7:B100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $idx = 7;
                $inventorySum = 0;
                foreach ($records as $record) {
                    if ($record['label'] == 'Total inventory value:') {
                        $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                        $sheet->setCellValue('A'.$idx, $record['label']);
                        $idx += 1;

                        foreach ($record['value'] as $value) {
                            $sheet->setCellValue('A'.$idx, '   ' . $value['label']);
                            $sheet->setCellValue('B'.$idx, number_format($value['value'], 2));

                            $inventorySum += $value['value'];
                            $idx += 1;
                        }

                        $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                        $sheet->setCellValue('A'.$idx, '   Total inventory');
                        $sheet->setCellValue('B'.$idx, number_format($inventorySum, 2));
                        $idx += 1;
                    }
                    else {
                        $sheet->getStyle('A'.$idx)->applyFromArray($styleArray);
                        $sheet->setCellValue('A'.$idx, $record['label']);
                        $sheet->setCellValue('B'.$idx, number_format($record['value'], 2));
                    }
                    $idx += 1;
                }

                $writer = new Xlsx($excel);
                $filename = rand();
                $created = Storage::disk('public')->put($filename, '');
                if ($created) {

                    $filepath = "../storage/app/public/$filename";
                    $writer->save($filepath);
            
                    return response()->download($filepath, 'CooperativeConsolidatedReport')->deleteFileAfterSend(true);

                } else {
                    throw new Exception("Failed! The report could not be generated", 1);
                }
            }

        } catch (Throwable $th) {
            Log::error($th);
            toastr()->error('Failed! Do you have Data for that Period?');
            return back();
        }
    }

    public function getStatementRecords($filter_data, $cooperative_id): \Illuminate\Support\Collection
    {
        $records = AccountingTransaction::select('accounting_transactions.accounting_ledger_id as id',
            DB::raw('SUM(accounting_transactions.debit) as debit'),
            DB::raw('SUM(accounting_transactions.credit) as credit'),
            DB::raw('MAX(accounting_ledgers.name) as name'),
            DB::raw('MAX(parent_ledgers.name) as description'))
            ->join('accounting_ledgers', 'accounting_ledgers.id', '=', 'accounting_transactions.accounting_ledger_id')
            ->join('parent_ledgers', 'parent_ledgers.id', '=', 'accounting_ledgers.parent_ledger_id')
            ->where('accounting_transactions.cooperative_id', $cooperative_id)
            ->whereBetween('date', [$filter_data['from'], $filter_data['to']])
            ->groupBy('accounting_transactions.accounting_ledger_id')
            ->get();
        return $records;
    }

    private function ledger_reports($financial_period, $ledger_account, $cooperative_id, $from, $to): array
    {
        $ledger = AccountingLedger::find($ledger_account);
        $filter_ranges = get_financial_period_statement_ranges($financial_period,$from, $to);
        $accounting_ledger_id = $ledger->id;
        $period = $filter_ranges["period"];
        $filter_data = $filter_ranges["filter_data"];
        $fy = CooperativeFinancialPeriod::find($financial_period);
        $records = EloquentBuilder::to(AccountingTransaction::where('accounting_ledger_id', $accounting_ledger_id)
            ->where('cooperative_id', $cooperative_id), $filter_data)->get();

        return [
            "ledger_account" => $ledger,
            "filtered_data" => $filter_data,
            "period" => $period,
            "records" => $records,
            "fy" => $fy
        ];

    }

    public function show_ledger_reports($financial_period, $ledger_account, Request $request)
    {
        $user = Auth::user();
        $coopid = $user->cooperative_id;
        $data = $this->ledger_reports($financial_period, $ledger_account,$coopid, $request->from, $request->to);
        $ledger_account = $data['ledger_account'];
        $period = $data['period'];
        $records = $data['records'];
        $fy = $data['fy'];
        $ledgers = $this->get_ledgers();
        $farmers = Farmer::select(['farmers.id', DB::raw('CONCAT(users.first_name, " ", users.other_names) AS name') ])
                        ->join('users', 'users.id', '=', 'farmers.user_id')
                        ->where('users.cooperative_id', $coopid)
                        ->get();

        return view('pages.cooperative.accounting.accounting_report',
            compact('ledger_account', 'period', 'records', 'fy', 'ledgers', 'farmers'));
    }

    private function get_ledgers(): \Illuminate\Support\Collection
    {
        return AccountingLedger::whereNull('deleted_at')
            ->where(function ($query) {
                $query->where('cooperative_id', Auth::user()->cooperative_id)->orWhereNull('cooperative_id');
            }
            )->orderBy('created_at', 'desc')->get();
    }

    public function print_ledger_reports($financial_period, $ledger_account, Request $request)
    {
        $data = $this->ledger_reports($financial_period, $ledger_account, Auth::user()->cooperative_id, $request->from, $request->to);
        $ledger_account = $data['ledger_account']->name;
        $filter_data = $data['filtered_data'];
        $period = $data['period'];
        $records = $data['records'];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pages.cooperative.accounting.pdf_views.ledgers', compact('ledger_account', 'period', 'records'));

        $file_name = strtolower($ledger_account . '_' . str_replace('-', '_', $filter_data['from']) . '_' . str_replace('-', '_', $filter_data['to'])).'.pdf';
        return $pdf->download($file_name);
    }


    public function show_profit_loss_reports($financial_period, Request $request)
    {
        $filter_ranges = get_financial_period_statement_ranges($financial_period, $request->from, $request->to);
        $from = $filter_ranges['filter_data']['from'];
        $to = $filter_ranges['filter_data']['to'];
        $cooperative = Auth::user()->cooperative_id;
        $income_expenses = IncomeAndExpense::where('cooperative_id', $cooperative)->whereBetween('date', [$from, $to])->get();
        $period = $filter_ranges['period'];
        $fy = CooperativeFinancialPeriod::find($financial_period);
        $balance_bf = $fy->balance_bf;
        $ledgers = $this->get_ledgers();

        return view('pages.cooperative.accounting.accounting_report',
            compact('financial_period', 'income_expenses', 'period', 'fy', 'ledgers', 'balance_bf'));
    }

    public function print_profit_loss_reports($financial_period, Request $request)
    {
        $filter_ranges = get_financial_period_statement_ranges($financial_period, $request->from, $request->to);
        $period = $filter_ranges['period'];
        $from = $filter_ranges['filter_data']['from'];
        $to = $filter_ranges['filter_data']['to'];
        $cooperative = Auth::user()->cooperative_id;
        $income_expenses = IncomeAndExpense::where('cooperative_id', $cooperative)->whereBetween('date', [$from, $to])->get();
        $fy = CooperativeFinancialPeriod::find($financial_period);
        $balance_bf = $fy->balance_bf;

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pages.cooperative.accounting.pdf_views.profit_loss', compact('period', 'income_expenses', 'balance_bf'));

        $file_name = strtolower('profit_and_loss_' . str_replace('-', '_', $from) . '_' . str_replace('-', '_', $to)).'.pdf';
        return $pdf->download($file_name);
    }

    private function getLedgerBalances($account, $startDate, $endDate, $coopid, $type = "")
    {
        $transactions = [];
        $bindings = [];

        if (preg_match('/(Assets|Expenses)/i', $account)) {

            $query = "SELECT
                    (
                        SUM(COALESCE(accounting_transactions.debit, 0)) - 
                        SUM(COALESCE(accounting_transactions.credit, 0)) 
                    ) AS debit,
                    '0' AS credit,
                    accounting_ledgers.name AS name,
                    parent_ledgers.name AS description,
                    accounting_ledgers.type AS type,
                    accounting_ledgers.id AS ledger_id
                FROM
                    accounting_transactions
                    JOIN accounting_ledgers ON accounting_ledgers.id = accounting_transactions.accounting_ledger_id
                    JOIN parent_ledgers ON parent_ledgers.id = accounting_ledgers.parent_ledger_id
                WHERE 
                    parent_ledgers.name = ? 
                    AND accounting_transactions.cooperative_id = ?
                    AND accounting_transactions.date BETWEEN ? AND ? ";

            $bindings = [ $account, $coopid, $startDate, $endDate ];

            if ($type != "") {
                $query .= " AND accounting_ledgers.type = ? ";
                $bindings = array_merge($bindings, [ $type ]);
            }

            $query .= "GROUP BY accounting_ledgers.name, parent_ledgers.name, accounting_ledgers.type, accounting_ledgers.id ";
            $transactions = DB::select($query, $bindings);
        }

        if (preg_match('/(Liabilities|Equity)/i', $account)) {

            $query = "SELECT
                    (
                        SUM(COALESCE(accounting_transactions.credit, 0)) - 
                        SUM(COALESCE(accounting_transactions.debit, 0)) 
                    ) AS credit,
                    '0' AS debit,
                    accounting_ledgers.name AS name,
                    parent_ledgers.name AS description,
                    accounting_ledgers.type AS type,
                    accounting_ledgers.id AS ledger_id
                FROM
                    accounting_transactions
                    JOIN accounting_ledgers ON accounting_ledgers.id = accounting_transactions.accounting_ledger_id
                    JOIN parent_ledgers ON parent_ledgers.id = accounting_ledgers.parent_ledger_id
                WHERE 
                    parent_ledgers.name = ? 
                    AND accounting_transactions.cooperative_id = ?
                    AND accounting_transactions.date BETWEEN ? AND ? ";

            $bindings = [ $account, $coopid, $startDate, $endDate ];

            if ($type != "") {
                $query .= " AND accounting_ledgers.type = ? ";
                $bindings = array_merge($bindings, [ $type ]);
            }

            $query .= "GROUP BY accounting_ledgers.name, parent_ledgers.name, accounting_ledgers.type, accounting_ledgers.id ";
            $transactions = DB::select($query, $bindings);
        }

        if (preg_match('/Revenue/i', $account)) {

            $transactions = DB::select("SELECT
                    (
                        SUM(COALESCE(accounting_transactions.credit, 0)) - 
                        SUM(COALESCE(accounting_transactions.debit, 0)) 
                    ) AS credit,
                    '0' AS debit,
                    accounting_ledgers.name AS name,
                    parent_ledgers.name AS description,
                    accounting_ledgers.type AS type,
                    accounting_ledgers.id AS ledger_id
                FROM
                    accounting_transactions
                    JOIN accounting_ledgers ON accounting_ledgers.id = accounting_transactions.accounting_ledger_id
                    JOIN parent_ledgers ON parent_ledgers.id = accounting_ledgers.parent_ledger_id
                WHERE 
                    parent_ledgers.name = ? 
                    AND accounting_transactions.cooperative_id = ?
                    AND accounting_transactions.date BETWEEN ? AND ? 
                GROUP BY 
                    accounting_ledgers.name, 
                    parent_ledgers.name, 
                    accounting_ledgers.type, 
                    accounting_ledgers.id",
                [ $account, $coopid, $startDate, $endDate ]);

            
        }

        return $transactions;
    }

    private function getFarmerBalances($what, $startDate, $endDate, $farmerId)
    {
        $amount = 0;

        if ($what == 'collections') {

            $amount = DB::select("SELECT
                    products.name,
                    IFNULL(SUM(collections.quantity * collections.unit_price), 0) AS amount
                FROM
                    collections
                    JOIN products ON products.id = collections.product_id
                    JOIN units ON units.id = products.unit_id
                WHERE
                    collections.farmer_id = ?
                    AND collections.date_collected BETWEEN ? AND ?
                GROUP BY
                    products.id", [ $farmerId, $startDate, $endDate ]);
        }

        if ($what == 'payments_made') {

            $payments = DB::select("SELECT
                    IFNULL(SUM(wallet_transactions.amount), 0) AS amount
                FROM
                    wallets
                    JOIN wallet_transactions ON wallet_transactions.wallet_id = wallets.id
                WHERE
                    wallets.farmer_id = ? 
                    AND wallet_transactions.type = 'payment'", [ $farmerId ]);

            $amount = count($payments) > 0 ? $payments[0]->amount : 0;
        }

        if ($what == 'loan_balance') {

            $total_loans = Loan::whereIn('status', [Loan::STATUS_APPROVED, Loan::STATUS_PARTIAL_REPAYMENT])
                ->where('farmer_id', $farmerId)
                ->where('balance', '>', 0)
                ->sum('balance');

            $group_loans = GroupLoan::whereIn('status', [GroupLoan::STATUS_DISBURSED, GroupLoan::STATUS_PARTIALLY_PAID])
                ->where('farmer_id', $farmerId)
                ->sum('balance');

            $amount = $total_loans + $group_loans;
        }

        if ($what == 'purchases') {

            $purchases = DB::select("SELECT
                    IFNULL(SUM((si.amount * si.quantity) - s.discount), 0) as amount
                FROM
                    sale_items si
                    JOIN sales s ON si.sales_id = s.id
                WHERE
                    s.farmer_id = ?
                    AND s.deleted_at is NULL",  [ $farmerId ]);

            $amount = count($purchases) > 0 ? $purchases[0]->amount : 0;
        }

        if ($what == 'payments_pending') {
            $wallet = Wallet::where('farmer_id', $farmerId)->first();
            $amount = $wallet ? $wallet->current_balance : 0;
        }

        if ($what == 'savings') {

            $savings = SavingAccount::where('farmer_id', $farmerId)
                ->where('status', SavingAccount::STATUS_ACTIVE)
                ->sum('amount');

            $amount = $savings;
        }

        return $amount;
    }

    private function getCooperativeBalances($what, $startDate, $endDate, $coopId)
    {
        $amount = 0;

        if ($what == 'sales') {

            $sales = DB::select("SELECT
                    IFNULL((SUM(sale_items.amount * sale_items.quantity) - SUM(sales.discount)), 0) AS amount 
                FROM
                    sales
                    JOIN sale_items ON sale_items.sales_id = sales.id
                WHERE
                    sales.cooperative_id = ? 
                    AND sales.created_at BETWEEN ? AND ? ", [ $coopId, $startDate, $endDate ]);

            $amount = count($sales) > 0 ? $sales[0]->amount : 0; 
        }

        if ($what == 'farmers') {

            $farmers = DB::select("SELECT 
                    IFNULL(COUNT(farmers.id), 0) AS count
                FROM 
                    farmers 
                    JOIN users ON users.id = farmers.user_id
                WHERE 
                    users.cooperative_id = ? 
                    AND farmers.created_at BETWEEN ? AND ? ", [ $coopId, $startDate, $endDate ]);

            $amount = count($farmers) > 0 ? $farmers[0]->count : 0; 
        }

        if ($what == 'customer_debts') {

            $debts = DB::select("SELECT
                    SUM(sales.balance) AS amount
                FROM
                    sales
                WHERE
                    sales.balance > 0
                    AND sales.cooperative_id = ?
                    AND sales.created_at BETWEEN ? AND ? ", [ $coopId, $startDate, $endDate ]);

            $amount = count($debts) > 0 ? $debts[0]->amount : 0;
        }

        if ($what == 'customer_payments') {

            $payments = DB::select("SELECT
                    IFNULL(SUM(amount), 0) AS amount
                FROM
                    wallet_transactions
                    JOIN wallets ON wallets.id = wallet_transactions.wallet_id
                    JOIN farmers ON farmers.id = wallets.farmer_id
                    JOIN users ON users.id = farmers.user_id
                WHERE
                    type = 'payment'
                    AND users.cooperative_id = ? 
                    AND wallet_transactions.created_at BETWEEN ? AND ? ", [ $coopId, $startDate, $endDate ]);

            $amount = count($payments) > 0 ? $payments[0]->amount : 0;
        }

        if ($what == 'expenses') {

            $expenses = DB::select("SELECT 
                    IFNULL(SUM(expense), 0) AS amount 
                FROM 
                    income_and_expenses 
                WHERE 
                    cooperative_id = ? 
                    AND income_and_expenses.date BETWEEN ? AND ? ", [ $coopId, $startDate, $endDate ]);

            $amount =  count($expenses) > 0 ? $expenses[0]->amount : 0;
        }

        if ($what == 'supplier_payments') {

            $supplier_payments = DB::select("SELECT 
                    IFNULL(SUM(expense), 0) AS amount 
                FROM 
                    income_and_expenses 
                WHERE 
                    particulars != 'Pay Farmer' 
                    AND cooperative_id = ? 
                    AND income_and_expenses.date BETWEEN ? AND ? ", [ $coopId, $startDate, $endDate ]);

            $amount =  count($supplier_payments) > 0 ? $supplier_payments[0]->amount : 0;
        }

        if ($what == 'loans_issued') {

            $loans = DB::select("SELECT
                    IFNULL(SUM(loans.amount), 0) AS amount
                FROM
                    loans
                    JOIN farmers ON farmers.id = loans.farmer_id
                    JOIN users ON users.id = farmers.user_id
                WHERE
                    loans.status = ? 
                    AND users.cooperative_id = ? 
                    AND loans.created_at BETWEEN ? AND ? ", 
                [ Loan::STATUS_PENDING, $coopId, $startDate, $endDate ]);

            $amount = count($loans) > 0 ? $loans[0]->amount : 0;
        }

        if ($what == 'loans_repaid') {

            $loans = DB::select("SELECT
                    IFNULL((SUM(loans.balance) - SUM(loans.amount)), 0) AS amount
                FROM
                    loans
                    JOIN farmers ON farmers.id = loans.farmer_id
                    JOIN users ON users.id = farmers.user_id
                WHERE
                    loans.status IN (" . Loan::STATUS_REPAID . ", " . Loan::STATUS_PARTIAL_REPAYMENT . ") 
                    AND users.cooperative_id = ? 
                    AND loans.created_at BETWEEN ? AND ? ", [ $coopId, $startDate, $endDate ]);

            $amount = count($loans) > 0 ? $loans[0]->amount : 0;
        }

        if ($what == 'coop_loan') {

            $coopLoans = DB::select("SELECT
                    IFNULL((SUM(accounting_transactions.credit) - SUM(accounting_transactions.debit)), 0) AS amount
                FROM
                    accounting_transactions
                    JOIN accounting_ledgers ON accounting_ledgers.id = accounting_transactions.accounting_ledger_id
                WHERE
                    accounting_ledgers.name = 'Loans'
                    AND accounting_transactions.cooperative_id = ? 
                    AND accounting_transactions.created_at BETWEEN ? AND ? ", [ $coopId, $startDate, $endDate ]);

            $amount = count($coopLoans) > 0 ? $coopLoans[0]->amount : 0;
        }

        if ($what == 'employees') {

            $employees = DB::select("SELECT 
                    count(coop_employees.id) AS count 
                FROM coop_employees
                    JOIN coop_branch_departments ON coop_branch_departments.id = coop_employees.department_id
                    JOIN coop_branches ON coop_branches.id = coop_branch_departments.branch_id
                WHERE 
                    status = ? 
                    AND coop_branches.cooperative_id = ? ", [ CoopEmployee::STATUS_ACTIVE, $coopId ]);

            $amount = count($employees) ? $employees[0]->count : 0;
        }

        if ($what == 'payroll') {

            $payrolls = DB::select("SELECT
                    IFNULL(SUM(basic_pay + total_allowances), 0) AS amount
                FROM
                    payrolls
                WHERE
                    cooperative_id = ?
                    AND created_at BETWEEN ? AND ? ", [ $coopId, $startDate, $endDate ]);

            $amount = count($payrolls) ? $payrolls[0]->amount : 0;
        }

        if ($what == 'inventory') {

            $rawMaterials = DB::select("SELECT
                    SUM(balance) AS amount
                FROM
                    raw_material_supply_histories
                WHERE
                    cooperative_id = ?
                    AND created_at BETWEEN ? AND ? ", [ $coopId, $startDate, $endDate ]);

            $finishedProducts = DB::select("SELECT
                    IFNULL(SUM(quantity * unit_price), 0) AS amount
                FROM
                    production_histories
                WHERE
                    cooperative_id = ?
                    AND created_at BETWEEN ? AND ? ", [ $coopId, $startDate, $endDate ]);

            $rawMaterialsSum = count($rawMaterials) ? $rawMaterials[0]->amount : 0;
            $finishedProductsSum = count($finishedProducts) ? $finishedProducts[0]->amount : 0;

            $amount = [
                [ 'label' => 'Raw Materials', 'value' => $rawMaterialsSum ],
                [ 'label' => 'Finished Products', 'value' => $finishedProductsSum ],
            ];
        }

        return $amount;
    }
}
