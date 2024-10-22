<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\AccountingLedger
 *
 * @property int $id
 * @property string $name
 * @property int $parent_ledger_id
 * @property string $type
 * @property int $ledger_code
 * @property string|null $description
 * @property string|null $classification
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read \App\AccountingRule|null $accounting_rule
 * @property-read \App\ParentLedger $parent_ledger
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger whereClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger whereLedgerCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger whereParentLedgerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingLedger whereUpdatedAt($value)
 */
	class AccountingLedger extends \Eloquent {}
}

namespace App{
/**
 * App\AccountingRule
 *
 * @property string $id
 * @property string $name
 * @property int $debit_ledger_id
 * @property int $credit_ledger_id
 * @property string|null $description
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\AccountingLedger $credit_ledger
 * @property-read \App\AccountingLedger $debit_ledger
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule whereCreditLedgerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule whereDebitLedgerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingRule whereUpdatedAt($value)
 */
	class AccountingRule extends \Eloquent {}
}

namespace App{
/**
 * App\AccountingTransaction
 *
 * @property string $id
 * @property string $ref_no
 * @property int $accounting_ledger_id
 * @property string $date
 * @property float|null $credit
 * @property float|null $debit
 * @property string|null $particulars
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $cooperative_id
 * @property-read \App\AccountingLedger $accounting_ledger
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction whereAccountingLedgerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction whereParticulars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction whereRefNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingTransaction whereUpdatedAt($value)
 */
	class AccountingTransaction extends \Eloquent {}
}

namespace App{
/**
 * App\AdvanceDeduction
 *
 * @property string $id
 * @property int $type
 * @property int $start_month
 * @property int $start_year
 * @property float $monthly_deductions
 * @property float $principal_amount
 * @property float $balance
 * @property int $status
 * @property int $deduction_period
 * @property string $employee_id
 * @property string $created_by
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\User $createdBy
 * @property-read \App\CoopEmployee $employee
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereDeductionPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereMonthlyDeductions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction wherePrincipalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereStartMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereStartYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeduction whereUpdatedAt($value)
 */
	class AdvanceDeduction extends \Eloquent {}
}

namespace App{
/**
 * App\AdvanceDeductionTransaction
 *
 * @property string $id
 * @property string $payroll_id
 * @property float $amount
 * @property float $balance
 * @property string $advance_deduction_id
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\AdvanceDeduction $advanceDeduction
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Payroll $payroll
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeductionTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeductionTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeductionTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeductionTransaction whereAdvanceDeductionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeductionTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeductionTransaction whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeductionTransaction whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeductionTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeductionTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeductionTransaction wherePayrollId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvanceDeductionTransaction whereUpdatedAt($value)
 */
	class AdvanceDeductionTransaction extends \Eloquent {}
}

namespace App{
/**
 * App\AuditTrail
 *
 * @property string $id
 * @property string|null $user_id
 * @property string $activity
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $company
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail query()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereUserId($value)
 */
	class AuditTrail extends \Eloquent {}
}

namespace App{
/**
 * App\Bank
 *
 * @property string $id
 * @property string $name
 * @property string $contact_no
 * @property string $swift_code
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereContactNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereSwiftCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereUpdatedAt($value)
 */
	class Bank extends \Eloquent {}
}

namespace App{
/**
 * App\BankBranch
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property string $address
 * @property string|null $cooperative_id
 * @property string|null $bank_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Bank|null $bank
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch query()
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BankBranch whereUpdatedAt($value)
 */
	class BankBranch extends \Eloquent {}
}

namespace App{
/**
 * App\Breed
 *
 * @property string $id
 * @property string $name
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Cow[] $cows
 * @property-read int|null $cows_count
 * @method static \Illuminate\Database\Eloquent\Builder|Breed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Breed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Breed query()
 * @method static \Illuminate\Database\Eloquent\Builder|Breed whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Breed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Breed whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Breed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Breed whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Breed whereUpdatedAt($value)
 */
	class Breed extends \Eloquent {}
}

namespace App{
/**
 * App\Budget
 *
 * @property string $id
 * @property string $type
 * @property string $year
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Budget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Budget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Budget query()
 * @method static \Illuminate\Database\Eloquent\Builder|Budget whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Budget whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Budget whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Budget whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Budget whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Budget whereYear($value)
 */
	class Budget extends \Eloquent {}
}

namespace App{
/**
 * App\BudgetAmount
 *
 * @property string $id
 * @property string $period
 * @property float $amount
 * @property string $ledger_id
 * @property string $budget_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BudgetAmount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BudgetAmount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BudgetAmount query()
 * @method static \Illuminate\Database\Eloquent\Builder|BudgetAmount whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BudgetAmount whereBudgetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BudgetAmount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BudgetAmount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BudgetAmount whereLedgerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BudgetAmount wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BudgetAmount whereUpdatedAt($value)
 */
	class BudgetAmount extends \Eloquent {}
}

namespace App{
/**
 * App\BulkPayment
 *
 * @property string $id
 * @property string $batch
 * @property float $total_amount
 * @property string $created_by_id
 * @property int $mode
 * @property int $status
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\User $createdBy
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment whereCreatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BulkPayment whereUpdatedAt($value)
 */
	class BulkPayment extends \Eloquent {}
}

namespace App{
/**
 * App\Category
 *
 * @property string $id
 * @property string $name
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App{
/**
 * App\Collection
 *
 * @property string $id
 * @property string $farmer_id
 * @property string $product_id
 * @property string $quantity
 * @property string|null $status
 * @property int $submission_status
 * @property string $date_collected
 * @property string|null $agent_id
 * @property string $cooperative_id
 * @property string|null $comments
 * @property int $collection_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $collection_number
 * @property string|null $batch_no
 * @property float|null $available_quantity
 * @property string|null $collection_quality_standard_id
 * @property float $unit_price
 * @property-read \App\User|null $agent
 * @property-read \App\CollectionQualityStandard|null $collection_quality_standard
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Farmer $farmer
 * @property-read \App\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sale[] $sales
 * @property-read int|null $sales_count
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereAvailableQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereBatchNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCollectionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCollectionQualityStandardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCollectionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereDateCollected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereSubmissionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUpdatedAt($value)
 */
	class Collection extends \Eloquent {}
}

namespace App{
/**
 * App\CollectionQualityStandard
 *
 * @property string $id
 * @property string $cooperative_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Collection[] $collections
 * @property-read int|null $collections_count
 * @property-read \App\Cooperative $cooperative
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionQualityStandard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionQualityStandard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionQualityStandard query()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionQualityStandard whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionQualityStandard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionQualityStandard whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionQualityStandard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionQualityStandard whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionQualityStandard whereUpdatedAt($value)
 */
	class CollectionQualityStandard extends \Eloquent {}
}

namespace App{
/**
 * App\CoopBranch
 *
 * @property string $id
 * @property string $name
 * @property string|null $code
 * @property string $cooperative_id
 * @property string $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranch newQuery()
 * @method static \Illuminate\Database\Query\Builder|CoopBranch onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranch query()
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranch whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranch whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranch whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|CoopBranch withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CoopBranch withoutTrashed()
 */
	class CoopBranch extends \Eloquent {}
}

namespace App{
/**
 * App\CoopBranchDepartment
 *
 * @property string $id
 * @property string $name
 * @property string|null $code
 * @property string|null $office_number
 * @property string $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\CoopBranch $coopBranch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CoopEmployee[] $departmentEmployee
 * @property-read int|null $department_employee_count
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranchDepartment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranchDepartment newQuery()
 * @method static \Illuminate\Database\Query\Builder|CoopBranchDepartment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranchDepartment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranchDepartment whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranchDepartment whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranchDepartment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranchDepartment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranchDepartment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranchDepartment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranchDepartment whereOfficeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopBranchDepartment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|CoopBranchDepartment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CoopBranchDepartment withoutTrashed()
 */
	class CoopBranchDepartment extends \Eloquent {}
}

namespace App{
/**
 * App\CoopEmployee
 *
 * @property string $id
 * @property string $country_id
 * @property string $county_of_residence
 * @property string|null $area_of_residence
 * @property string|null $marital_status
 * @property string $dob
 * @property string $gender
 * @property string|null $id_no
 * @property string|null $phone_no
 * @property string|null $employee_no
 * @property string|null $kra
 * @property string|null $nhif_no
 * @property string|null $nssf_no
 * @property string $department_id
 * @property string $user_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\EmployeeBankDetail|null $bankDetails
 * @property-read \App\CoopBranch $coopBranch
 * @property-read \App\Country $country
 * @property-read \App\CoopBranchDepartment $department
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EmployeeAllowance[] $employeeAllowance
 * @property-read int|null $employee_allowance_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EmployeeLeave[] $employeeLeave
 * @property-read int|null $employee_leave_count
 * @property-read \App\EmployeeSalary|null $employeeSalary
 * @property-read \App\EmployeeEmploymentType|null $employmentType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EmployeeFile[] $files
 * @property-read int|null $files_count
 * @property-read \App\EmployeePosition|null $position
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee newQuery()
 * @method static \Illuminate\Database\Query\Builder|CoopEmployee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereAreaOfResidence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereCountyOfResidence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereEmployeeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereIdNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereKra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereNhifNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereNssfNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee wherePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoopEmployee whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|CoopEmployee withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CoopEmployee withoutTrashed()
 */
	class CoopEmployee extends \Eloquent {}
}

namespace App{
/**
 * App\Cooperative
 *
 * @property string $id
 * @property int $default_coop
 * @property string $name
 * @property string|null $abbreviation
 * @property string|null $country_id
 * @property string $location
 * @property string $rate_type
 * @property string $address
 * @property string $email
 * @property string $contact_details
 * @property string|null $logo
 * @property string $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Country|null $country
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sale[] $sales
 * @property-read int|null $sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereContactDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereDefaultCoop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereRateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cooperative whereUpdatedAt($value)
 */
	class Cooperative extends \Eloquent {}
}

namespace App{
/**
 * App\CooperativeFinancialPeriod
 *
 * @property string $id
 * @property string $cooperative_id
 * @property string $start_period
 * @property string $end_period
 * @property string $type
 * @property float|null $balance_cf
 * @property float $balance_bf
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod query()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod whereBalanceBf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod whereBalanceCf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod whereEndPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod whereStartPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeFinancialPeriod whereUpdatedAt($value)
 */
	class CooperativeFinancialPeriod extends \Eloquent {}
}

namespace App{
/**
 * App\CooperativeInternalRole
 *
 * @property string $id
 * @property string $role
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $modules
 * @property-read int|null $modules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\InternalRolePermission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeInternalRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeInternalRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeInternalRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeInternalRole whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeInternalRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeInternalRole whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeInternalRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeInternalRole whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeInternalRole whereUpdatedAt($value)
 */
	class CooperativeInternalRole extends \Eloquent {}
}

namespace App{
/**
 * App\CooperativePaymentConfigs
 *
 * @property string $id
 * @property string $shortcode
 * @property string $name
 * @property string $type
 * @property string $consumer_key
 * @property string $consumer_secret
 * @property string $passkey
 * @property string $initiator_name
 * @property string $initiator_pass
 * @property string $status
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs query()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereConsumerKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereConsumerSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereInitiatorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereInitiatorPass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs wherePasskey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereShortcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativePaymentConfigs whereUpdatedAt($value)
 */
	class CooperativePaymentConfigs extends \Eloquent {}
}

namespace App{
/**
 * App\CooperativeProperty
 *
 * @property string $id
 * @property string $cooperative_id
 * @property string $property
 * @property string $type
 * @property float $value
 * @property float $deprecation_rate_pa
 * @property float|null $selling_price
 * @property float $buying_price
 * @property string|null $documents
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty query()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereBuyingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereDeprecationRatePa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereProperty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeProperty whereValue($value)
 */
	class CooperativeProperty extends \Eloquent {}
}

namespace App{
/**
 * App\CooperativeWallet
 *
 * @property string $id
 * @property float $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $cooperative_id
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWallet whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWallet whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWallet whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWallet whereUpdatedAt($value)
 */
	class CooperativeWallet extends \Eloquent {}
}

namespace App{
/**
 * App\CooperativeWalletTransaction
 *
 * @property string $id
 * @property string $cooperative_wallet_id
 * @property float $amount
 * @property string $type
 * @property string $description
 * @property string|null $reference
 * @property string|null $source
 * @property string $date
 * @property string|null $proof_of_payment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\CooperativeWallet $cooperative_wallet
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereCooperativeWalletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereProofOfPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CooperativeWalletTransaction whereUpdatedAt($value)
 */
	class CooperativeWalletTransaction extends \Eloquent {}
}

namespace App{
/**
 * App\Country
 *
 * @property string $id
 * @property string $name
 * @property string $iso_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Cooperative[] $cooperatives
 * @property-read int|null $cooperatives_count
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIsoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 */
	class Country extends \Eloquent {}
}

namespace App{
/**
 * App\Cow
 *
 * @property string $id
 * @property string $name
 * @property string $animal_type
 * @property string|null $tag_name
 * @property string|null $breed_id
 * @property string|null $farmer_id
 * @property int $approval_status
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Breed|null $breed
 * @property-read \App\Farmer|null $farmer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CropCalendarStage[] $stages
 * @property-read int|null $stages_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cow query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cow whereAnimalType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cow whereApprovalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cow whereBreedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cow whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cow whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cow whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cow whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cow whereTagName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cow whereUpdatedAt($value)
 */
	class Cow extends \Eloquent {}
}

namespace App{
/**
 * App\Crop
 *
 * @property string $id
 * @property string|null $product_id
 * @property string $variety
 * @property string|null $farm_unit_id
 * @property string $recommended_areas
 * @property string|null $description
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\FarmUnit|null $farm_unit
 * @property-read \App\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CropCalendarStage[] $stages
 * @property-read int|null $stages_count
 * @method static \Illuminate\Database\Eloquent\Builder|Crop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Crop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Crop query()
 * @method static \Illuminate\Database\Eloquent\Builder|Crop whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crop whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crop whereFarmUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crop whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crop whereRecommendedAreas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crop whereVariety($value)
 */
	class Crop extends \Eloquent {}
}

namespace App{
/**
 * App\CropCalendarStage
 *
 * @property string $id
 * @property int $type
 * @property string $name
 * @property int $period
 * @property string $period_measure
 * @property string|null $crop_id
 * @property string|null $livestock_id
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Crop|null $crop
 * @property-read \App\Cow|null $livestock
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage whereCropId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage whereLivestockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage wherePeriodMeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStage whereUpdatedAt($value)
 */
	class CropCalendarStage extends \Eloquent {}
}

namespace App{
/**
 * App\CropCalendarStageCostBreakdown
 *
 * @property string $id
 * @property string $item
 * @property int $amount
 * @property string|null $tracker_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\FarmerCropProgressTracker|null $tracker
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStageCostBreakdown newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStageCostBreakdown newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStageCostBreakdown query()
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStageCostBreakdown whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStageCostBreakdown whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStageCostBreakdown whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStageCostBreakdown whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStageCostBreakdown whereTrackerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CropCalendarStageCostBreakdown whereUpdatedAt($value)
 */
	class CropCalendarStageCostBreakdown extends \Eloquent {}
}

namespace App{
/**
 * App\Customer
 *
 * @property string $id
 * @property int $customer_type
 * @property string $name
 * @property string|null $title
 * @property string|null $gender
 * @property string $email
 * @property string $phone_number
 * @property string $last_visit
 * @property string|null $location
 * @property string|null $address
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative|null $cooperative
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sale[] $sales
 * @property-read int|null $sales_count
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereLastVisit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 */
	class Customer extends \Eloquent {}
}

namespace App{
/**
 * App\Disease
 *
 * @property string $id
 * @property string $name
 * @property string|null $disease_category_id
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\DiseaseCategory|null $disease_category
 * @method static \Illuminate\Database\Eloquent\Builder|Disease newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Disease newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Disease query()
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereDiseaseCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereUpdatedAt($value)
 */
	class Disease extends \Eloquent {}
}

namespace App{
/**
 * App\DiseaseCategory
 *
 * @property string $id
 * @property string $name
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseCategory whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseCategory whereUpdatedAt($value)
 */
	class DiseaseCategory extends \Eloquent {}
}

namespace App{
/**
 * App\EmployeeAllowance
 *
 * @property string $id
 * @property float $amount
 * @property string $type
 * @property string $title
 * @property string|null $description
 * @property string $employee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property float|null $percentage
 * @property-read \App\CoopEmployee $employee
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance newQuery()
 * @method static \Illuminate\Database\Query\Builder|EmployeeAllowance onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAllowance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|EmployeeAllowance withTrashed()
 * @method static \Illuminate\Database\Query\Builder|EmployeeAllowance withoutTrashed()
 */
	class EmployeeAllowance extends \Eloquent {}
}

namespace App{
/**
 * App\EmployeeAppraisal
 *
 * @property string $id
 * @property string $employee_id
 * @property int $appraisal_type
 * @property string $effective_date
 * @property string $old_position_id
 * @property string $new_position_id
 * @property string $old_job_group
 * @property string $new_job_group
 * @property string $old_department_id
 * @property string $new_department_id
 * @property float $old_salary
 * @property float $new_salary
 * @property string $old_employment_type_id
 * @property string $new_employment_type_id
 * @property string $comments
 * @property string $actioned_by_id
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\User $actionedBy
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\CoopEmployee $employee
 * @property-read \App\CoopBranchDepartment $newDepartment
 * @property-read \App\EmployeeEmploymentType $newEmploymentType
 * @property-read \App\EmployeePosition $newPosition
 * @property-read \App\CoopBranchDepartment $oldDepartment
 * @property-read \App\EmployeeEmploymentType $oldEmploymentType
 * @property-read \App\EmployeePosition $oldPosition
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal newQuery()
 * @method static \Illuminate\Database\Query\Builder|EmployeeAppraisal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereActionedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereAppraisalType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereNewDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereNewEmploymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereNewJobGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereNewPositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereNewSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereOldDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereOldEmploymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereOldJobGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereOldPositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereOldSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAppraisal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|EmployeeAppraisal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|EmployeeAppraisal withoutTrashed()
 */
	class EmployeeAppraisal extends \Eloquent {}
}

namespace App{
/**
 * App\EmployeeBankDetail
 *
 * @property string $id
 * @property string $employee_id
 * @property string $account_name
 * @property string $account_number
 * @property string|null $bank_id
 * @property string $bank_branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Bank|null $bank
 * @property-read \App\BankBranch $bankBranch
 * @property-read \App\BankBranch $employee
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail newQuery()
 * @method static \Illuminate\Database\Query\Builder|EmployeeBankDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail whereBankBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeBankDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|EmployeeBankDetail withTrashed()
 * @method static \Illuminate\Database\Query\Builder|EmployeeBankDetail withoutTrashed()
 */
	class EmployeeBankDetail extends \Eloquent {}
}

namespace App{
/**
 * App\EmployeeDisciplinary
 *
 * @property string $id
 * @property string $employee_id
 * @property string $effective_date
 * @property int|null $days
 * @property string|null $end_date
 * @property int $with_pay
 * @property int $disciplinary_type
 * @property string $reason
 * @property int $status
 * @property string $actioned_by
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $actionedBy
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\CoopEmployee $employee
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereActionedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereDisciplinaryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDisciplinary whereWithPay($value)
 */
	class EmployeeDisciplinary extends \Eloquent {}
}

namespace App{
/**
 * App\EmployeeEmploymentType
 *
 * @property string $id
 * @property string $employment_type_id
 * @property string $employee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\CoopEmployee $employee
 * @property-read \App\EmploymentType $employeeType
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeEmploymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeEmploymentType newQuery()
 * @method static \Illuminate\Database\Query\Builder|EmployeeEmploymentType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeEmploymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeEmploymentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeEmploymentType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeEmploymentType whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeEmploymentType whereEmploymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeEmploymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeEmploymentType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|EmployeeEmploymentType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|EmployeeEmploymentType withoutTrashed()
 */
	class EmployeeEmploymentType extends \Eloquent {}
}

namespace App{
/**
 * App\EmployeeFile
 *
 * @property string $id
 * @property string $employee_id
 * @property string $file_name
 * @property string $file_link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\CoopEmployee $employee
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeFile newQuery()
 * @method static \Illuminate\Database\Query\Builder|EmployeeFile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeFile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeFile whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeFile whereFileLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeFile whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|EmployeeFile withTrashed()
 * @method static \Illuminate\Database\Query\Builder|EmployeeFile withoutTrashed()
 */
	class EmployeeFile extends \Eloquent {}
}

namespace App{
/**
 * App\EmployeeLeave
 *
 * @property string $id
 * @property string $start_date
 * @property string $end_date
 * @property string|null $reason
 * @property string|null $remarks
 * @property string|null $file
 * @property int $status
 * @property string $employee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\CoopEmployee $employee
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave newQuery()
 * @method static \Illuminate\Database\Query\Builder|EmployeeLeave onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLeave whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|EmployeeLeave withTrashed()
 * @method static \Illuminate\Database\Query\Builder|EmployeeLeave withoutTrashed()
 */
	class EmployeeLeave extends \Eloquent {}
}

namespace App{
/**
 * App\EmployeePosition
 *
 * @property string $id
 * @property string $position_id
 * @property string $employee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\CoopEmployee $employee
 * @property-read \App\JobPosition $position
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeePosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeePosition newQuery()
 * @method static \Illuminate\Database\Query\Builder|EmployeePosition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeePosition query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeePosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeePosition whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeePosition whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeePosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeePosition wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeePosition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|EmployeePosition withTrashed()
 * @method static \Illuminate\Database\Query\Builder|EmployeePosition withoutTrashed()
 */
	class EmployeePosition extends \Eloquent {}
}

namespace App{
/**
 * App\EmployeeSalary
 *
 * @property string $id
 * @property float $amount
 * @property string|null $job_group
 * @property string|null $has_benefits
 * @property string $employee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\CoopEmployee $employee
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalary newQuery()
 * @method static \Illuminate\Database\Query\Builder|EmployeeSalary onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalary query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalary whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalary whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalary whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalary whereHasBenefits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalary whereJobGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|EmployeeSalary withTrashed()
 * @method static \Illuminate\Database\Query\Builder|EmployeeSalary withoutTrashed()
 */
	class EmployeeSalary extends \Eloquent {}
}

namespace App{
/**
 * App\EmploymentType
 *
 * @property string $id
 * @property string $type
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EmployeeEmploymentType[] $typeEmployees
 * @property-read int|null $type_employees_count
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentType newQuery()
 * @method static \Illuminate\Database\Query\Builder|EmploymentType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentType whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|EmploymentType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|EmploymentType withoutTrashed()
 */
	class EmploymentType extends \Eloquent {}
}

namespace App{
/**
 * App\ExpiredProductionProduct
 *
 * @property string $id
 * @property string $production_history_id
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\ProductionHistory $production_history
 * @method static \Illuminate\Database\Eloquent\Builder|ExpiredProductionProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpiredProductionProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpiredProductionProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpiredProductionProduct whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpiredProductionProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpiredProductionProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpiredProductionProduct whereProductionHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpiredProductionProduct whereUpdatedAt($value)
 */
	class ExpiredProductionProduct extends \Eloquent {}
}

namespace App{
/**
 * App\FarmUnit
 *
 * @property string $id
 * @property string $name
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @method static \Illuminate\Database\Eloquent\Builder|FarmUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmUnit whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmUnit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmUnit whereUpdatedAt($value)
 */
	class FarmUnit extends \Eloquent {}
}

namespace App{
/**
 * App\Farmer
 *
 * @property string $id
 * @property string|null $country_id
 * @property string $county
 * @property string|null $location_id
 * @property string $id_no
 * @property string $phone_no
 * @property string|null $route_id
 * @property string|null $bank_account
 * @property string|null $bank_branch_id
 * @property string $member_no
 * @property string $customer_type
 * @property string $kra
 * @property string|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property float $farm_size
 * @property int $age
 * @property string $dob
 * @property string $gender
 * @property-read \App\BankBranch|null $bank_branch
 * @property-read \App\Country|null $country
 * @property-read \App\LoanLimit|null $limit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Cow[] $livestock
 * @property-read int|null $livestock_count
 * @property-read \App\Location|null $location
 * @property-read \App\Route|null $route
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sale[] $sales
 * @property-read int|null $sales_count
 * @property-read \App\User|null $user
 * @property-read \App\Wallet|null $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereBankBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereFarmSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereIdNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereKra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereMemberNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer wherePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farmer whereUserId($value)
 */
	class Farmer extends \Eloquent {}
}

namespace App{
/**
 * App\FarmerCrop
 *
 * @property string $id
 * @property int $type
 * @property string $farmer_id
 * @property string|null $crop_id
 * @property string|null $livestock_id
 * @property string $stage_id
 * @property string|null $start_date
 * @property string $last_date
 * @property string|null $next_stage_id
 * @property float $total_cost
 * @property string $status
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Crop|null $crop
 * @property-read \App\Farmer $farmer
 * @property-read \App\Cow|null $livestock
 * @property-read \App\CropCalendarStage|null $next_stage
 * @property-read \App\CropCalendarStage $stage
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop query()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereCropId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereLastDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereLivestockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereNextStageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereStageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCrop whereUpdatedAt($value)
 */
	class FarmerCrop extends \Eloquent {}
}

namespace App{
/**
 * App\FarmerCropProgressTracker
 *
 * @property string $id
 * @property string $farmer_crop_id
 * @property string $stage_id
 * @property string|null $start_date
 * @property string $last_date
 * @property string|null $next_stage_id
 * @property float $cost
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CropCalendarStageCostBreakdown[] $costBreakDowns
 * @property-read int|null $cost_break_downs_count
 * @property-read \App\FarmerCrop $farmer_crop
 * @property-read \App\CropCalendarStage|null $next_stage
 * @property-read \App\CropCalendarStage $stage
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker query()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker whereFarmerCropId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker whereLastDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker whereNextStageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker whereStageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerCropProgressTracker whereUpdatedAt($value)
 */
	class FarmerCropProgressTracker extends \Eloquent {}
}

namespace App{
/**
 * App\FarmerExpectedYield
 *
 * @property string $id
 * @property float $quantity
 * @property string|null $crop_id
 * @property string|null $livestock_breed_id
 * @property string $volume_indicator
 * @property string $farm_unit_id
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Crop|null $crop
 * @property-read \App\FarmUnit $farm_unit
 * @property-read \App\Breed|null $livestock_breed
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield query()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield whereCropId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield whereFarmUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield whereLivestockBreedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerExpectedYield whereVolumeIndicator($value)
 */
	class FarmerExpectedYield extends \Eloquent {}
}

namespace App{
/**
 * App\FarmerYield
 *
 * @property string $id
 * @property string $farmer_id
 * @property string $type
 * @property string|null $crop_id
 * @property string|null $livestock_breed_id
 * @property string|null $product
 * @property string|null $date
 * @property string|null $to_date
 * @property string|null $expected_yields_id
 * @property float $volume_indicator_count
 * @property float $yields
 * @property string $unit_id
 * @property int $frequency_type
 * @property string|null $comments
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Crop|null $crop
 * @property-read \App\FarmerExpectedYield|null $expected_yields
 * @property-read \App\Farmer $farmer
 * @property-read \App\Breed|null $livestock_breed
 * @property-read \App\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield query()
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereCropId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereExpectedYieldsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereFrequencyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereLivestockBreedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereToDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereVolumeIndicatorCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FarmerYield whereYields($value)
 */
	class FarmerYield extends \Eloquent {}
}

namespace App{
/**
 * App\FinalProduct
 *
 * @property string $id
 * @property string $name
 * @property string|null $category_id
 * @property string $cooperative_id
 * @property string|null $unit_id
 * @property float|null $selling_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Category|null $category
 * @property-read \App\Cooperative $cooperative
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Production[] $production
 * @property-read int|null $production_count
 * @property-read \App\Unit|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct newQuery()
 * @method static \Illuminate\Database\Query\Builder|FinalProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinalProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|FinalProduct withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FinalProduct withoutTrashed()
 */
	class FinalProduct extends \Eloquent {}
}

namespace App{
/**
 * App\GroupLoan
 *
 * @property int $id
 * @property float $amount
 * @property float $balance
 * @property int $status
 * @property string $farmer_id
 * @property int $group_loan_summary_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Farmer $farmer
 * @property-read \App\GroupLoanSummary $group_loan_summery
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan newQuery()
 * @method static \Illuminate\Database\Query\Builder|GroupLoan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan whereGroupLoanSummaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|GroupLoan withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GroupLoan withoutTrashed()
 */
	class GroupLoan extends \Eloquent {}
}

namespace App{
/**
 * App\GroupLoanConfig
 *
 * @property string $id
 * @property int $number_of_loans_allowed
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanConfig whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanConfig whereNumberOfLoansAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanConfig whereUpdatedAt($value)
 */
	class GroupLoanConfig extends \Eloquent {}
}

namespace App{
/**
 * App\GroupLoanRepayment
 *
 * @property string $id
 * @property int $amount
 * @property int $status
 * @property string $initiated_by_id
 * @property int $group_loan_id
 * @property int $source
 * @property string|null $merchant_request_id
 * @property string|null $checkout_request_id
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\GroupLoan $group_loan
 * @property-read \App\User $initiated_by
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment whereCheckoutRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment whereGroupLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment whereInitiatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment whereMerchantRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanRepayment whereUpdatedAt($value)
 */
	class GroupLoanRepayment extends \Eloquent {}
}

namespace App{
/**
 * App\GroupLoanSummary
 *
 * @property int $id
 * @property int $number_of_farmers
 * @property float $total_amount
 * @property string $group_loan_type_id
 * @property \App\User $created_by
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\GroupLoanType $group_loan_type
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary whereGroupLoanTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary whereNumberOfFarmers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanSummary whereUpdatedAt($value)
 */
	class GroupLoanSummary extends \Eloquent {}
}

namespace App{
/**
 * App\GroupLoanType
 *
 * @property string $id
 * @property string $name
 * @property string $created_by
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\User $createdBy
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanType newQuery()
 * @method static \Illuminate\Database\Query\Builder|GroupLoanType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanType query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanType whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupLoanType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|GroupLoanType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GroupLoanType withoutTrashed()
 */
	class GroupLoanType extends \Eloquent {}
}

namespace App{
/**
 * App\IncomeAndExpense
 *
 * @property string $id
 * @property float|null $income
 * @property float|null $expense
 * @property string $date
 * @property string $particulars
 * @property string $user_id
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense query()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense whereExpense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense whereIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense whereParticulars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeAndExpense whereUserId($value)
 */
	class IncomeAndExpense extends \Eloquent {}
}

namespace App{
/**
 * App\InsuranceBenefit
 *
 * @property string $id
 * @property string $name
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\InsuranceProduct[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceBenefit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceBenefit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceBenefit query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceBenefit whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceBenefit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceBenefit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceBenefit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceBenefit whereUpdatedAt($value)
 */
	class InsuranceBenefit extends \Eloquent {}
}

namespace App{
/**
 * App\InsuranceClaim
 *
 * @property string $id
 * @property int $subscription_id
 * @property float $amount
 * @property int $status
 * @property string|null $dependant_id
 * @property string|null $description
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\InsuranceDependant|null $dependant
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\InsuranceClaimStatusTracker[] $status_trackers
 * @property-read int|null $status_trackers_count
 * @property-read \App\InsuranceSubscriber $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim whereDependantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaim whereUpdatedAt($value)
 */
	class InsuranceClaim extends \Eloquent {}
}

namespace App{
/**
 * App\InsuranceClaimLimit
 *
 * @property string $id
 * @property string $product_id
 * @property float $limit_rate
 * @property float $amount
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\InsuranceProduct $product
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimLimit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimLimit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimLimit query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimLimit whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimLimit whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimLimit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimLimit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimLimit whereLimitRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimLimit whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimLimit whereUpdatedAt($value)
 */
	class InsuranceClaimLimit extends \Eloquent {}
}

namespace App{
/**
 * App\InsuranceClaimStatusTracker
 *
 * @property int $id
 * @property string $claim_id
 * @property int $status
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\InsuranceClaim $claim
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimStatusTracker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimStatusTracker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimStatusTracker query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimStatusTracker whereClaimId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimStatusTracker whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimStatusTracker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimStatusTracker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimStatusTracker whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceClaimStatusTracker whereUpdatedAt($value)
 */
	class InsuranceClaimStatusTracker extends \Eloquent {}
}

namespace App{
/**
 * App\InsuranceDependant
 *
 * @property string $id
 * @property int $subscription_id
 * @property string $name
 * @property int $relationship
 * @property string $idno
 * @property string $dob
 * @property int $no
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\InsuranceSubscriber $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant whereIdno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant whereNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant whereRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceDependant whereUpdatedAt($value)
 */
	class InsuranceDependant extends \Eloquent {}
}

namespace App{
/**
 * App\InsuranceInstallment
 *
 * @property string $id
 * @property int $subscription_id
 * @property float $amount
 * @property float $amount_paid
 * @property int $status
 * @property string $due_date
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\InsuranceSubscriber $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceInstallment whereUpdatedAt($value)
 */
	class InsuranceInstallment extends \Eloquent {}
}

namespace App{
/**
 * App\InsurancePaymentModeAdjustedRate
 *
 * @property int $id
 * @property int $payment_mode
 * @property float $adjusted_rate
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InsurancePaymentModeAdjustedRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsurancePaymentModeAdjustedRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsurancePaymentModeAdjustedRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsurancePaymentModeAdjustedRate whereAdjustedRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsurancePaymentModeAdjustedRate whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsurancePaymentModeAdjustedRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsurancePaymentModeAdjustedRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsurancePaymentModeAdjustedRate wherePaymentMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsurancePaymentModeAdjustedRate whereUpdatedAt($value)
 */
	class InsurancePaymentModeAdjustedRate extends \Eloquent {}
}

namespace App{
/**
 * App\InsuranceProduct
 *
 * @property string $id
 * @property int $type
 * @property string $name
 * @property float $premium
 * @property float $interest
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\InsuranceBenefit[] $benefits
 * @property-read int|null $benefits_count
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceProduct whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceProduct whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceProduct whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceProduct wherePremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceProduct whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceProduct whereUpdatedAt($value)
 */
	class InsuranceProduct extends \Eloquent {}
}

namespace App{
/**
 * App\InsuranceSubscriber
 *
 * @property int $id
 * @property string $farmer_id
 * @property string|null $insurance_valuation_id
 * @property string $insurance_product_id
 * @property int $status
 * @property float $interest
 * @property int $payment_mode
 * @property float $period
 * @property string $expiry_date
 * @property float $amount_claimed
 * @property float $adjusted_premium
 * @property float $penalty
 * @property int $grace_period
 * @property float|null $current_limit
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\InsuranceDependant[] $dependants
 * @property-read int|null $dependants_count
 * @property-read \App\Farmer $farmer
 * @property-read \App\InsuranceProduct $insurance_product
 * @property-read \App\InsuranceValuation|null $insurance_valuation
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereAdjustedPremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereAmountClaimed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereCurrentLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereGracePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereInsuranceProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereInsuranceValuationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber wherePaymentMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber wherePenalty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceSubscriber whereUpdatedAt($value)
 */
	class InsuranceSubscriber extends \Eloquent {}
}

namespace App{
/**
 * App\InsuranceTransactionHistory
 *
 * @property string $id
 * @property int $subscription_id
 * @property float $amount
 * @property int $type
 * @property string $date
 * @property string $created_by
 * @property string $comments
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\User $createdBy
 * @property-read \App\InsuranceSubscriber $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceTransactionHistory whereUpdatedAt($value)
 */
	class InsuranceTransactionHistory extends \Eloquent {}
}

namespace App{
/**
 * App\InsuranceValuation
 *
 * @property string $id
 * @property string $farmer_id
 * @property string $type
 * @property float $amount
 * @property string $description
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Farmer $farmer
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceValuation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceValuation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceValuation query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceValuation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceValuation whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceValuation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceValuation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceValuation whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceValuation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceValuation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceValuation whereUpdatedAt($value)
 */
	class InsuranceValuation extends \Eloquent {}
}

namespace App{
/**
 * App\InternalRolePermission
 *
 * @property string $id
 * @property string|null $internal_role_id
 * @property string $cooperative_id
 * @property int $can_view
 * @property int $can_create
 * @property int $can_edit
 * @property int $can_delete
 * @property int $can_download_report
 * @property string $created_by_user_id
 * @property string|null $updated_by_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $createdBy
 * @property-read \App\CooperativeInternalRole|null $internalRole
 * @property-read \App\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereCanCreate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereCanDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereCanDownloadReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereCanEdit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereCanView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereCreatedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereInternalRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalRolePermission whereUpdatedByUserId($value)
 */
	class InternalRolePermission extends \Eloquent {}
}

namespace App{
/**
 * App\InternalUserPermission
 *
 * @property string $id
 * @property string $employee_id
 * @property string $submodule_id
 * @property string $cooperative_id
 * @property int $can_view
 * @property int $can_create
 * @property int $can_edit
 * @property int $can_delete
 * @property int $can_download_report
 * @property string $created_by_user_id
 * @property string|null $updated_by_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\User $createdBy
 * @property-read \App\User $employee
 * @property-read \App\SystemSubmodule $subModule
 * @property-read \App\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereCanCreate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereCanDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereCanDownloadReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereCanEdit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereCanView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereCreatedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereSubmoduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternalUserPermission whereUpdatedByUserId($value)
 */
	class InternalUserPermission extends \Eloquent {}
}

namespace App{
/**
 * App\Invoice
 *
 * @property string $id
 * @property string|null $sale_id
 * @property string $invoice_number
 * @property int $invoice_count
 * @property int $status
 * @property string $date
 * @property int $delivery_status
 * @property string|null $delivery_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\InvoicePayment[] $invoice_payments
 * @property-read int|null $invoice_payments_count
 * @property-read \App\Sale|null $sale
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Query\Builder|Invoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Invoice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Invoice withoutTrashed()
 */
	class Invoice extends \Eloquent {}
}

namespace App{
/**
 * App\InvoicePayment
 *
 * @property string $id
 * @property string|null $invoice_id
 * @property float $amount
 * @property string $transaction_number
 * @property string $payment_platform
 * @property string|null $merchant_request_id
 * @property string|null $checkout_request_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $instructions
 * @property string|null $initiator
 * @property-read \App\User|null $initiated_by
 * @property-read \App\Invoice|null $invoice
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereCheckoutRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereInitiator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereMerchantRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment wherePaymentPlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereTransactionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicePayment whereUpdatedAt($value)
 */
	class InvoicePayment extends \Eloquent {}
}

namespace App{
/**
 * App\JobPosition
 *
 * @property string $id
 * @property string $position
 * @property string|null $role
 * @property string|null $code
 * @property string|null $description
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EmployeePosition[] $employeePosition
 * @property-read int|null $employee_position_count
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition newQuery()
 * @method static \Illuminate\Database\Query\Builder|JobPosition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition query()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|JobPosition withTrashed()
 * @method static \Illuminate\Database\Query\Builder|JobPosition withoutTrashed()
 */
	class JobPosition extends \Eloquent {}
}

namespace App{
/**
 * App\LNMTransaction
 *
 * @property int $id
 * @property string $merchant_request_id
 * @property string $checkout_request_id
 * @property string $result_code
 * @property string $result_description
 * @property float $amount
 * @property string|null $receipt
 * @property string|null $transaction_date
 * @property string $phone_number
 * @property int $status
 * @property string|null $farmer_id
 * @property string|null $customer_id
 * @property string $cooperative_id
 * @property string $model_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Farmer|null $farmer
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereCheckoutRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereMerchantRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereModelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereReceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereResultCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereResultDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LNMTransaction whereUpdatedAt($value)
 */
	class LNMTransaction extends \Eloquent {}
}

namespace App{
/**
 * App\LimitRateConfig
 *
 * @property string $id
 * @property float $rate
 * @property int $needs_approval
 * @property float|null $limit_for_approval
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @method static \Illuminate\Database\Eloquent\Builder|LimitRateConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LimitRateConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LimitRateConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|LimitRateConfig whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LimitRateConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LimitRateConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LimitRateConfig whereLimitForApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LimitRateConfig whereNeedsApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LimitRateConfig whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LimitRateConfig whereUpdatedAt($value)
 */
	class LimitRateConfig extends \Eloquent {}
}

namespace App{
/**
 * App\Loan
 *
 * @property int $id
 * @property float $amount
 * @property float $balance
 * @property string $status
 * @property string $farmer_id
 * @property string $due_date
 * @property string $mode_of_payment
 * @property float $interest
 * @property string $purpose
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $loan_setting_id
 * @property float|null $bought_off_at
 * @property int|null $bought_off_loan_id
 * @property-read Loan|null $bought_off_loan
 * @property-read \App\Farmer $farmer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LoanInstallment[] $loanInstallments
 * @property-read int|null $loan_installments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LoanRepayment[] $loanRepayment
 * @property-read int|null $loan_repayment_count
 * @property-read \App\LoanSetting $loan_setting
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereBoughtOffAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereBoughtOffLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereModeOfPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereUpdatedAt($value)
 */
	class Loan extends \Eloquent {}
}

namespace App{
/**
 * App\LoanApplicationDetail
 *
 * @property int $id
 * @property int $loan_id
 * @property int $has_farm_tools
 * @property int $has_land
 * @property int $has_livestock
 * @property float $original_rate
 * @property float $rate_applied
 * @property float $wallet_balance
 * @property float $average_cash_flow
 * @property float $pending_payments
 * @property float $limit
 * @property string|null $supporting_document
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Loan $loan
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereAverageCashFlow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereHasFarmTools($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereHasLand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereHasLivestock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereOriginalRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail wherePendingPayments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereRateApplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereSupportingDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplicationDetail whereWalletBalance($value)
 */
	class LoanApplicationDetail extends \Eloquent {}
}

namespace App{
/**
 * App\LoanInstallment
 *
 * @property string $id
 * @property int|null $loan_id
 * @property float $amount
 * @property float $repaid_amount
 * @property string $date
 * @property string $status
 * @property int $source
 * @property string|null $merchant_request_id
 * @property string|null $checkout_request_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Loan|null $loan
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereCheckoutRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereMerchantRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereRepaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanInstallment whereUpdatedAt($value)
 */
	class LoanInstallment extends \Eloquent {}
}

namespace App{
/**
 * App\LoanLimit
 *
 * @property string $id
 * @property string $farmer_id
 * @property float|null $limit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Farmer $farmer
 * @method static \Illuminate\Database\Eloquent\Builder|LoanLimit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanLimit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanLimit query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanLimit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanLimit whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanLimit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanLimit whereLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanLimit whereUpdatedAt($value)
 */
	class LoanLimit extends \Eloquent {}
}

namespace App{
/**
 * App\LoanPaymentHistory
 *
 * @property string $id
 * @property int|null $loan_id
 * @property string $wallet_transaction_id
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Loan|null $loan
 * @property-read \App\WalletTransaction $wallet_transaction
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPaymentHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPaymentHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPaymentHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPaymentHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPaymentHistory whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPaymentHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPaymentHistory whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPaymentHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPaymentHistory whereWalletTransactionId($value)
 */
	class LoanPaymentHistory extends \Eloquent {}
}

namespace App{
/**
 * App\LoanRepayment
 *
 * @property string $id
 * @property int|null $loan_id
 * @property string $wallet_transaction_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Loan|null $loan
 * @property-read \App\WalletTransaction $wallet_transaction
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRepayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRepayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRepayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRepayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRepayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRepayment whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRepayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanRepayment whereWalletTransactionId($value)
 */
	class LoanRepayment extends \Eloquent {}
}

namespace App{
/**
 * App\LoanSetting
 *
 * @property string $id
 * @property string $type
 * @property float $interest
 * @property float $penalty
 * @property int $period
 * @property string $installments
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting whereInstallments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting wherePenalty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanSetting whereUpdatedAt($value)
 */
	class LoanSetting extends \Eloquent {}
}

namespace App{
/**
 * App\Location
 *
 * @property string $id
 * @property string $cooperative_id
 * @property string $place_id
 * @property string $name
 * @property string $latitude
 * @property string $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 */
	class Location extends \Eloquent {}
}

namespace App{
/**
 * App\ManufacturingStore
 *
 * @property string $id
 * @property string $name
 * @property string $location
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ManufacturingStore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManufacturingStore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManufacturingStore query()
 * @method static \Illuminate\Database\Eloquent\Builder|ManufacturingStore whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManufacturingStore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManufacturingStore whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManufacturingStore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManufacturingStore whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManufacturingStore whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManufacturingStore whereUpdatedAt($value)
 */
	class ManufacturingStore extends \Eloquent {}
}

namespace App{
/**
 * App\ParentLedger
 *
 * @property int $id
 * @property string $name
 * @property int $parent_ledger_code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AccountingLedger[] $accounting_ledgers
 * @property-read int|null $accounting_ledgers_count
 * @method static \Illuminate\Database\Eloquent\Builder|ParentLedger newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParentLedger newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParentLedger query()
 * @method static \Illuminate\Database\Eloquent\Builder|ParentLedger whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParentLedger whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParentLedger whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParentLedger whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParentLedger whereParentLedgerCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParentLedger whereUpdatedAt($value)
 */
	class ParentLedger extends \Eloquent {}
}

namespace App{
/**
 * App\Payroll
 *
 * @property string $id
 * @property string $employee_id
 * @property int $period_month
 * @property int $period_year
 * @property float $gross_pay
 * @property float $net_pay
 * @property float $basic_pay
 * @property float $total_allowances
 * @property string|null $allowances
 * @property string|null $before_tax_deductions
 * @property string|null $after_tax_deductions
 * @property string|null $advance_deductions
 * @property float $taxable_income
 * @property float $paye_before_deduction
 * @property string $paye_deduction
 * @property float $paye
 * @property int $employee_status
 * @property string $created_by
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\User $createdBy
 * @property-read \App\CoopEmployee $employee
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll newQuery()
 * @method static \Illuminate\Database\Query\Builder|Payroll onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereAdvanceDeductions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereAfterTaxDeductions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereAllowances($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereBasicPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereBeforeTaxDeductions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereEmployeeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereGrossPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereNetPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll wherePaye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll wherePayeBeforeDeduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll wherePayeDeduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll wherePeriodMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll wherePeriodYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereTaxableIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereTotalAllowances($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Payroll withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Payroll withoutTrashed()
 */
	class Payroll extends \Eloquent {}
}

namespace App{
/**
 * App\PayrollDeduction
 *
 * @property string $id
 * @property string $name
 * @property int $deduction_stage
 * @property float|null $min_amount
 * @property float|null $max_amount
 * @property float|null $amount
 * @property float|null $rate
 * @property int $on_gross_pay
 * @property string $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Country $country
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction query()
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction whereDeductionStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction whereMaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction whereMinAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction whereOnGrossPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollDeduction whereUpdatedAt($value)
 */
	class PayrollDeduction extends \Eloquent {}
}

namespace App{
/**
 * App\PayrollStatus
 *
 * @property string $id
 * @property int $status
 * @property string $payroll_id
 * @property string $created_by
 * @property string|null $updated_by
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Payroll $payroll
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus newQuery()
 * @method static \Illuminate\Database\Query\Builder|PayrollStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus wherePayrollId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayrollStatus whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|PayrollStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PayrollStatus withoutTrashed()
 */
	class PayrollStatus extends \Eloquent {}
}

namespace App{
/**
 * App\Product
 *
 * @property string $id
 * @property string $name
 * @property string|null $cooperative_id
 * @property string $mode
 * @property float $sale_price
 * @property float $vat
 * @property string|null $serial_number
 * @property string|null $image
 * @property string|null $category_id
 * @property string|null $unit_id
 * @property float $threshold
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property float $buying_price
 * @property-read \App\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Collection[] $collections
 * @property-read int|null $collections_count
 * @property-read \App\Cooperative|null $cooperative
 * @property-read \App\Crop|null $crop
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $farmers
 * @property-read int|null $farmers_count
 * @property-read \App\Unit|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBuyingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVat($value)
 */
	class Product extends \Eloquent {}
}

namespace App{
/**
 * App\Production
 *
 * @property string $id
 * @property string $final_product_id
 * @property string $quantity
 * @property string|null $final_selling_price
 * @property string|null $manufacturing_store_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property float $available_quantity
 * @property string|null $cooperative_id
 * @property-read \App\FinalProduct $finalProduct
 * @property-read \App\ManufacturingStore|null $manufacturing_store
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductionMaterial[] $rawMaterials
 * @property-read int|null $raw_materials_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sale[] $sales
 * @property-read int|null $sales_count
 * @property-read \App\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|Production newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Production newQuery()
 * @method static \Illuminate\Database\Query\Builder|Production onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Production query()
 * @method static \Illuminate\Database\Eloquent\Builder|Production whereAvailableQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Production whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Production whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Production whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Production whereFinalProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Production whereFinalSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Production whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Production whereManufacturingStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Production whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Production whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Production withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Production withoutTrashed()
 */
	class Production extends \Eloquent {}
}

namespace App{
/**
 * App\ProductionHistory
 *
 * @property string $id
 * @property string $production_id
 * @property string $production_lot
 * @property float $quantity
 * @property float $unit_price
 * @property string $user_id
 * @property int $expires
 * @property string|null $expiry_date
 * @property int $expiry_status
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Production $production
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RawMaterial[] $raw_materials
 * @property-read int|null $raw_materials_count
 * @property-read \App\User $registered_by
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereExpires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereExpiryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereProductionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereProductionLot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionHistory whereUserId($value)
 */
	class ProductionHistory extends \Eloquent {}
}

namespace App{
/**
 * App\ProductionMaterial
 *
 * @property string $id
 * @property string $production_history_id
 * @property string $raw_material_id
 * @property string|null $cooperative_id
 * @property float $cost
 * @property string $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Cooperative|null $cooperative
 * @property-read \App\ProductionHistory $productionHistory
 * @property-read \App\RawMaterial $rawMaterial
 * @property-read \App\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductionMaterial onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial whereProductionHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial whereRawMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionMaterial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ProductionMaterial withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductionMaterial withoutTrashed()
 */
	class ProductionMaterial extends \Eloquent {}
}

namespace App{
/**
 * App\ProductionStockTracker
 *
 * @property string $id
 * @property string $final_product_id
 * @property float $selling_price
 * @property string $date
 * @property float $opening_quantity
 * @property float $opening_stock_value
 * @property float $closing_stock
 * @property float $closing_stock_value
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\FinalProduct $final_product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker whereClosingStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker whereClosingStockValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker whereFinalProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker whereOpeningQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker whereOpeningStockValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionStockTracker whereUpdatedAt($value)
 */
	class ProductionStockTracker extends \Eloquent {}
}

namespace App{
/**
 * App\RawMaterial
 *
 * @property string $id
 * @property string|null $name
 * @property float|null $estimated_cost
 * @property string|null $unit_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $cooperative_id
 * @property-read \App\Cooperative|null $cooperative
 * @property-read \App\Unit|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterial newQuery()
 * @method static \Illuminate\Database\Query\Builder|RawMaterial onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterial whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterial whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterial whereEstimatedCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterial whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterial whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|RawMaterial withTrashed()
 * @method static \Illuminate\Database\Query\Builder|RawMaterial withoutTrashed()
 */
	class RawMaterial extends \Eloquent {}
}

namespace App{
/**
 * App\RawMaterialInventory
 *
 * @property string $id
 * @property string $raw_material_id
 * @property float $quantity
 * @property float $value
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\RawMaterial $raw_material
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialInventory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialInventory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialInventory query()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialInventory whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialInventory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialInventory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialInventory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialInventory whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialInventory whereRawMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialInventory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialInventory whereValue($value)
 */
	class RawMaterialInventory extends \Eloquent {}
}

namespace App{
/**
 * App\RawMaterialSupplyHistory
 *
 * @property string $id
 * @property string $raw_material_id
 * @property int $supply_type
 * @property string|null $supplier_id
 * @property string|null $product_id
 * @property string $supply_date
 * @property float $amount
 * @property float $balance
 * @property float $quantity
 * @property int $payment_status
 * @property string|null $store_id
 * @property string $details
 * @property string $purchase_number
 * @property int $delivery_status
 * @property string $cooperative_id
 * @property string $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\ManufacturingStore|null $manufacturing_store
 * @property-read \App\Product|null $product_collection
 * @property-read \App\RawMaterial $raw_material
 * @property-read \App\Supplier|null $supplier
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory wherePurchaseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereRawMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereSupplyDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereSupplyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyHistory whereUserId($value)
 */
	class RawMaterialSupplyHistory extends \Eloquent {}
}

namespace App{
/**
 * App\RawMaterialSupplyPayment
 *
 * @property string $id
 * @property float $amount
 * @property float $balance
 * @property string $supply_history_id
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\RawMaterialSupplyHistory $supply_history
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyPayment whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyPayment whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyPayment whereSupplyHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawMaterialSupplyPayment whereUpdatedAt($value)
 */
	class RawMaterialSupplyPayment extends \Eloquent {}
}

namespace App{
/**
 * App\Recruitment
 *
 * @property string $id
 * @property string $role
 * @property string|null $description
 * @property string|null $desired_skills
 * @property string|null $qualifications
 * @property string|null $employment_type
 * @property string|null $salary_range
 * @property string|null $location
 * @property string|null $file
 * @property int $status
 * @property string $end_date
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment newQuery()
 * @method static \Illuminate\Database\Query\Builder|Recruitment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereDesiredSkills($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereEmploymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereQualifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereSalaryRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recruitment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Recruitment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Recruitment withoutTrashed()
 */
	class Recruitment extends \Eloquent {}
}

namespace App{
/**
 * App\RecruitmentApplication
 *
 * @property string $id
 * @property string $surname
 * @property string $othernames
 * @property string|null $phone
 * @property string $email
 * @property string|null $area_of_residence
 * @property string|null $qualification
 * @property string|null $top_skills
 * @property string $resume
 * @property string $cover_letter
 * @property int $status
 * @property string $recruitment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Recruitment $recruitment
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication newQuery()
 * @method static \Illuminate\Database\Query\Builder|RecruitmentApplication onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereAreaOfResidence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereCoverLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereOthernames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereQualification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereRecruitmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereResume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereTopSkills($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecruitmentApplication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|RecruitmentApplication withTrashed()
 * @method static \Illuminate\Database\Query\Builder|RecruitmentApplication withoutTrashed()
 */
	class RecruitmentApplication extends \Eloquent {}
}

namespace App{
/**
 * App\ReportedCase
 *
 * @property string $id
 * @property string $farmer_id
 * @property string $disease_id
 * @property string $symptoms
 * @property string $status
 * @property int $booked
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Disease $disease
 * @property-read \App\User $farmer
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase whereBooked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase whereDiseaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase whereSymptoms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportedCase whereUpdatedAt($value)
 */
	class ReportedCase extends \Eloquent {}
}

namespace App{
/**
 * App\ReturnedItem
 *
 * @property string $id
 * @property string $sale_id
 * @property string|null $collection_id
 * @property string|null $manufactured_product_id
 * @property float $quantity
 * @property float $amount
 * @property string $date
 * @property string $notes
 * @property string $served_by_id
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Collection|null $collection
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Production|null $manufactured_product
 * @property-read \App\Sale $sale
 * @property-read \App\User $served_by
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereManufacturedProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereServedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnedItem whereUpdatedAt($value)
 */
	class ReturnedItem extends \Eloquent {}
}

namespace App{
/**
 * App\Route
 *
 * @property string $id
 * @property string $name
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Route newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Route newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Route query()
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Route whereUpdatedAt($value)
 */
	class Route extends \Eloquent {}
}

namespace App{
/**
 * App\Sale
 *
 * @property string $id
 * @property string|null $farmer_id
 * @property string|null $user_id
 * @property string|null $cooperative_id
 * @property string|null $customer_id
 * @property string $sale_batch_number
 * @property int $sale_count
 * @property string $date
 * @property float $discount
 * @property float $paid_amount
 * @property float $balance
 * @property string|null $notes
 * @property string $save_type
 * @property int $recurring
 * @property string|null $toc
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $type
 * @property-read \App\Collection $collection
 * @property-read \App\Cooperative|null $cooperative
 * @property-read \App\Customer|null $customer
 * @property-read \App\Farmer|null $farmer
 * @property-read \App\Invoice|null $invoices
 * @property-read \App\Production $manufactured_product
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SaleItem[] $saleItems
 * @property-read int|null $sale_items_count
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale newQuery()
 * @method static \Illuminate\Database\Query\Builder|Sale onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereSaleBatchNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereSaleCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereSaveType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereToc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Sale withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Sale withoutTrashed()
 */
	class Sale extends \Eloquent {}
}

namespace App{
/**
 * App\SaleItem
 *
 * @property string $id
 * @property string|null $manufactured_product_id
 * @property string|null $collection_id
 * @property string|null $sales_id
 * @property float $amount
 * @property float $quantity
 * @property float $discount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Collection|null $collection
 * @property-read \App\Production|null $manufactured_product
 * @property-read \App\Sale|null $sale
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem newQuery()
 * @method static \Illuminate\Database\Query\Builder|SaleItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereManufacturedProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereSalesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SaleItem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SaleItem withoutTrashed()
 */
	class SaleItem extends \Eloquent {}
}

namespace App{
/**
 * App\SavingAccount
 *
 * @property int $id
 * @property float $amount
 * @property string $date_started
 * @property string $maturity_date
 * @property float $interest
 * @property int $status
 * @property string $farmer_id
 * @property string $saving_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Farmer $farmer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SavingInstallment[] $saving_trail
 * @property-read int|null $saving_trail_count
 * @property-read \App\SavingType $saving_type
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount whereDateStarted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount whereMaturityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount whereSavingTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingAccount whereUpdatedAt($value)
 */
	class SavingAccount extends \Eloquent {}
}

namespace App{
/**
 * App\SavingInstallment
 *
 * @property string $id
 * @property int|null $saving_id
 * @property string $wallet_transaction_id
 * @property-read \App\SavingAccount|null $saving_account
 * @property-read \App\WalletTransaction $wallet_transaction
 * @method static \Illuminate\Database\Eloquent\Builder|SavingInstallment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingInstallment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingInstallment query()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingInstallment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingInstallment whereSavingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingInstallment whereWalletTransactionId($value)
 */
	class SavingInstallment extends \Eloquent {}
}

namespace App{
/**
 * App\SavingType
 *
 * @property string $id
 * @property int $period
 * @property float $interest
 * @property string $type
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @method static \Illuminate\Database\Eloquent\Builder|SavingType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingType query()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingType whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingType whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingType wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingType whereUpdatedAt($value)
 */
	class SavingType extends \Eloquent {}
}

namespace App{
/**
 * App\Supplier
 *
 * @property string $id
 * @property int $supplier_type
 * @property string $name
 * @property string|null $title
 * @property string|null $gender
 * @property string $email
 * @property string $phone_number
 * @property string $location
 * @property string $address
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereSupplierType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereUpdatedAt($value)
 */
	class Supplier extends \Eloquent {}
}

namespace App{
/**
 * App\SystemModule
 *
 * @property string $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CooperativeInternalRole[] $cooperative_roles
 * @property-read int|null $cooperative_roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SystemSubmodule[] $subModules
 * @property-read int|null $sub_modules_count
 * @method static \Illuminate\Database\Eloquent\Builder|SystemModule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemModule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemModule query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemModule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemModule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemModule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemModule whereUpdatedAt($value)
 */
	class SystemModule extends \Eloquent {}
}

namespace App{
/**
 * App\SystemSubmodule
 *
 * @property string $id
 * @property string $name
 * @property string $module_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\SystemModule $module
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSubmodule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSubmodule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSubmodule query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSubmodule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSubmodule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSubmodule whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSubmodule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSubmodule whereUpdatedAt($value)
 */
	class SystemSubmodule extends \Eloquent {}
}

namespace App{
/**
 * App\TransportProvider
 *
 * @property string $id
 * @property string $name
 * @property string $phone_number
 * @property string $location
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TransportProviderVehicle[] $vehicles
 * @property-read int|null $vehicles_count
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProvider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProvider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProvider query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProvider whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProvider whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProvider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProvider whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProvider whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProvider wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProvider whereUpdatedAt($value)
 */
	class TransportProvider extends \Eloquent {}
}

namespace App{
/**
 * App\TransportProviderVehicle
 *
 * @property string $id
 * @property string $registration_number
 * @property string $cooperative_id
 * @property string $transport_provider_id
 * @property string $vehicle_type_id
 * @property float $weight vehicle weight in kgs
 * @property string $driver_name
 * @property string|null $phone_no
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\VehicleType $type
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle whereDriverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle wherePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle whereTransportProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle whereVehicleTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransportProviderVehicle whereWeight($value)
 */
	class TransportProviderVehicle extends \Eloquent {}
}

namespace App{
/**
 * App\Trip
 *
 * @property string $id
 * @property string $cooperative_id
 * @property string $transport_type
 * @property string $transport_provider_id
 * @property string $vehicle_id
 * @property string $driver_name
 * @property string $driver_phone_number
 * @property string $load_type
 * @property string $load_unit
 * @property float $trip_distance trip distance in  kms
 * @property float $trip_cost_per_km
 * @property float $trip_cost_per_kg
 * @property float $trip_cost_total
 * @property int $status
 * @property string $status_date
 * @property string $status_comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TripLocation[] $locations
 * @property-read int|null $locations_count
 * @property-read \App\TransportProvider|null $transportProvider
 * @property-read \App\TransportProviderVehicle|null $transporterVehicle
 * @property-read \App\Unit|null $unit
 * @property-read \App\Vehicle|null $vehicle
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\WeighBridgeEvent[] $weighbridgeEvents
 * @property-read int|null $weighbridge_events_count
 * @method static \Illuminate\Database\Eloquent\Builder|Trip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereDriverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereDriverPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereLoadType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereLoadUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereStatusComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereStatusDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTransportProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTransportType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTripCostPerKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTripCostPerKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTripCostTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTripDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereVehicleId($value)
 */
	class Trip extends \Eloquent {}
}

namespace App{
/**
 * App\TripLocation
 *
 * @property string $id
 * @property string $cooperative_id
 * @property string $trip_id
 * @property string|null $location_id
 * @property string $type
 * @property string $datetime
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Location|null $location
 * @property-read \App\Trip $trip
 * @property-read \App\WeighBridgeEvent|null $weighBridgeEvent
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation whereTripId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripLocation whereUpdatedAt($value)
 */
	class TripLocation extends \Eloquent {}
}

namespace App{
/**
 * App\Unit
 *
 * @property string $id
 * @property string $name
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedAt($value)
 */
	class Unit extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property string $id
 * @property string $first_name
 * @property string $other_names
 * @property string|null $cooperative_id
 * @property string $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property int $status
 * @property string|null $profile_picture
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AuditTrail[] $audit_trails
 * @property-read int|null $audit_trails_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \App\Cooperative|null $cooperative
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CooperativeInternalRole[] $cooperative_roles
 * @property-read int|null $cooperative_roles_count
 * @property-read \App\CoopEmployee|null $employee
 * @property-read \App\Farmer|null $farmer
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Vet|null $vet
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $vet_items
 * @property-read int|null $vet_items_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOtherNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Vehicle
 *
 * @property string $id
 * @property string $registration_number
 * @property string $cooperative_id
 * @property string $user_id
 * @property string $vehicle_type_id
 * @property float $weight vehicle weight in kgs
 * @property int $status
 * @property string $status_date
 * @property string $status_comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\User $driver
 * @property-read \App\VehicleType $type
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereStatusComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereStatusDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereVehicleTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereWeight($value)
 */
	class Vehicle extends \Eloquent {}
}

namespace App{
/**
 * App\VehicleType
 *
 * @property string $id
 * @property string $name
 * @property string $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleType query()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleType whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleType whereUpdatedAt($value)
 */
	class VehicleType extends \Eloquent {}
}

namespace App{
/**
 * App\Vet
 *
 * @property string $id
 * @property string $phone_no
 * @property string $id_no
 * @property string $gender
 * @property string|null $profile_image
 * @property string $category
 * @property string|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Vet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vet whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vet whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vet whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vet whereIdNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vet wherePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vet whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vet whereUserId($value)
 */
	class Vet extends \Eloquent {}
}

namespace App{
/**
 * App\VetBooking
 *
 * @property string $id
 * @property string $event_start
 * @property string $event_end
 * @property string $event_name
 * @property string|null $farmer_id
 * @property string|null $vet_id
 * @property string|null $reported_case_id
 * @property string $booking_type
 * @property string|null $service_id
 * @property string $status
 * @property float $charges
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User|null $farmer
 * @property-read \App\VetService|null $service
 * @property-read \App\User|null $vet
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\VetItem[] $vet_items
 * @property-read int|null $vet_items_count
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking query()
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereBookingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereCharges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereEventEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereEventName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereEventStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereReportedCaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetBooking whereVetId($value)
 */
	class VetBooking extends \Eloquent {}
}

namespace App{
/**
 * App\VetItem
 *
 * @property string $id
 * @property string $name
 * @property float $quantity
 * @property float $sold_quantity
 * @property float $bp
 * @property float $sp
 * @property string|null $unit_id
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Unit|null $unit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\VetBooking[] $vet_bookings
 * @property-read int|null $vet_bookings_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $vets
 * @property-read int|null $vets_count
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem whereBp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem whereSoldQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem whereSp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetItem whereUpdatedAt($value)
 */
	class VetItem extends \Eloquent {}
}

namespace App{
/**
 * App\VetService
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string $description
 * @property string|null $cooperative_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|VetService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VetService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VetService query()
 * @method static \Illuminate\Database\Eloquent\Builder|VetService whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetService whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetService whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetService whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetService whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VetService whereUpdatedAt($value)
 */
	class VetService extends \Eloquent {}
}

namespace App{
/**
 * App\Wallet
 *
 * @property string $id
 * @property float $available_balance
 * @property float $current_balance
 * @property string $farmer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Farmer $farmer
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereAvailableBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCurrentBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereFarmerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUpdatedAt($value)
 */
	class Wallet extends \Eloquent {}
}

namespace App{
/**
 * App\WalletTransaction
 *
 * @property string $id
 * @property string $wallet_id
 * @property string $type
 * @property float $amount
 * @property string $reference
 * @property string $source
 * @property string $initiator_id
 * @property string $description
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $org_conv_id
 * @property string|null $conv_id
 * @property int $status
 * @property-read \App\User $initiator
 * @property-read \App\Wallet $wallet
 * @property-read \Illuminate\Database\Eloquent\Collection|WalletTransaction[] $wallet_transactions
 * @property-read int|null $wallet_transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereConvId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereInitiatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereOrgConvId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereWalletId($value)
 */
	class WalletTransaction extends \Eloquent {}
}

namespace App{
/**
 * App\WeighBridge
 *
 * @property string $id
 * @property string $cooperative_id
 * @property string|null $location_id
 * @property string $code
 * @property float $max_weight weighbridge weight limit in kgs
 * @property int $status
 * @property string $status_date
 * @property string $status_comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Location|null $location
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\WeighBridgeEvent[] $weighbridgeEvents
 * @property-read int|null $weighbridge_events_count
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge query()
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge whereMaxWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge whereStatusComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge whereStatusDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridge whereUpdatedAt($value)
 */
	class WeighBridge extends \Eloquent {}
}

namespace App{
/**
 * App\WeighBridgeEvent
 *
 * @property string $id
 * @property string $cooperative_id
 * @property string $weigh_bridge_id
 * @property string $trip_id
 * @property string $trip_location_id
 * @property float|null $weight
 * @property string|null $datetime
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Cooperative $cooperative
 * @property-read \App\Trip $trip
 * @property-read \App\TripLocation $tripLocation
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent whereCooperativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent whereTripId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent whereTripLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent whereWeighBridgeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeighBridgeEvent whereWeight($value)
 */
	class WeighBridgeEvent extends \Eloquent {}
}

