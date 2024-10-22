<template>
  <form @submit.prevent="submit" method="post">
    <div class="form-row" :hidden="isNext">

      <div class="form-group col-lg-3 col-md-6 col-12">
        <label for="product">Who is buying</label>
        <multiselect v-model="fields.buyers" label="who" :options="buyers" @input="onChange"
                     :close-on-select="true" :show-labels="false"
                     placeholder="Select Buyer"></multiselect>
      </div>

      <div class="form-group col-lg-3 col-md-6 col-12" :hidden="isHiddenFarmer" id="farmerB">
        <label for="farmer">Farmer</label>
        <multiselect v-model="fields.farmerObj" :custom-label="customLabelName" :options="farmers"
                     :close-on-select="true" @input="onChangeFarmer" :show-labels="false"
                     placeholder="Select Farmer"></multiselect>
        <div v-if="errors && errors.farmer" class="text-danger">{{ errors.farmer[0] }}</div>
      </div>
      <div class="form-group col-lg-3 col-md-6 col-12" :hidden="isHiddenCustomer" id="customerB">
        <label for="customer">Customer</label>
        <multiselect label="name" v-model="fields.customerObj" :options="customers"
                     :close-on-select="true" @input="onChangeCustomer" :show-labels="false"
                     placeholder="Select Customer"></multiselect>
        <div v-if="errors && errors.customer" class="text-danger">{{ errors.customer[0] }}</div>
      </div>
      <div class="form-group col-lg-3 col-md-6 col-12">
        <label for="due_date">Due Date</label>
        <input v-model="fields.due_date" label="Due Date" type="date" class="form-control"/>
        <div v-if="errors && errors.due_date" class="text-danger">{{ errors.due_date[0] }}</div>
      </div>
    </div>
    <!-- next section -->
    <div class="form-row" :hidden="!isNext">
      <div class="form-group col-lg-2 col-md-6 col-12">
        <label for="product">What to sell</label>
        <multiselect label="name" v-model="fields.whatObj" :options="whatToSell"
                     :close-on-select="true" @input="onChangeWhat" :show-labels="false"
                     placeholder="Select What to Sell"></multiselect>

        <div v-if="errors && errors.what_to_sell" class="text-danger">{{
            errors.what_to_sell[0]
          }}
        </div>
      </div>
      <div class="form-group col-lg-2 col-md-6 col-12" id="collectionP" :hidden="isHiddenCollected">
        <label for="product">Collected Product</label>
        <multiselect :custom-label="customLabel1" v-model="fields.productObj" :options="products"
                     :close-on-select="true" :show-labels="false" @input="onChangeCollected"
                     placeholder="Select Product"></multiselect>

        <div v-if="errors && errors.product" class="text-danger">{{ errors.product[0] }}</div>
      </div>

      <div class="form-group col-lg-2 col-md-6 col-12" id="manufacturedP"
           :hidden="isHiddenManufactured">
        <label for="manufactured">Manufactured Product</label>
        <multiselect v-model="fields.mfgObj" :custom-label="customLabel" :options="manufactureds"
                     :close-on-select="true" @input="onChangeManufactured" :show-labels="false"
                     placeholder="Select Product"></multiselect>

        <div v-if="errors && errors.manufactured" class="text-danger">{{
            errors.manufactured[0]
          }}
        </div>
      </div>

      <div class="form-group col-lg-2 col-md-6 col-12">
        <label for="amount">Rate</label>
        <input type="text" v-model="fields.amount" class="form-control" id="amount"
               placeholder="100.60">
        <div v-if="errors && errors.amount" class="text-danger">{{ errors.amount[0] }}</div>
      </div>


      <div class="form-group col-lg-2 col-md-6 col-12">
        <label for="quantity"> Quantity</label>
        <input type="text" v-model="fields.quantity"
               class="form-control "
               id="quantity" placeholder="e.g 1">
        <div v-if="errors && errors.quantity" class="text-danger">{{ errors.quantity[0] }}</div>
      </div>
      <div class="form-group col-lg-2 col-md-6 col-12 mb-1" :hidden="!isNext">
        <button type="button" @click="pushItems" class="btn btn-info pull-right mt-5"><i
            class="fa fa-plus-square"></i> Add
        </button>

      </div>

    </div>
    <div class="form-row" :hidden="isNext">
      <div class="form-group col-lg-3 col-md-6 col-12">
        <button type="button" @click="loadNext" class="btn btn-warning pull-right">Next ></button>
      </div>
    </div>
    <span v-if="fields.items.length > 0">
            <div class="form-row" :hidden="!isNext" v-for="(item, index) in fields.items"
                 :key="item.id">
                <div class="form-group col-lg-2 col-md-6 col-12">
                    <multiselect label="name" :options="[item.what]" v-model="item.what"
                                 :close-on-select="true" :show-labels="false"
                                 placeholder="Select What to Sell"></multiselect>
                
                    <div v-if="errors && errors.what_to_sell"
                         class="text-danger">{{ errors.what_to_sell[0] }}</div>
                </div>
                <div class="form-group col-lg-3 col-md-6 col-12" id="collectionP"
                     :hidden="isHiddenCollected">
                    <multiselect v-model="item.productName" :options="[item.productName]"
                                 placeholder="Select Product"/>
                    
                    <div v-if="errors && errors.product" class="text-danger">{{
                        errors.product[0]
                      }}</div>
                </div>

                <div class="form-group col-lg-3 col-md-6 col-12" id="manufacturedP"
                     :hidden="isHiddenManufactured">
                    <multiselect v-model="item.productName" :options="[item.productName]"
                                 placeholder="Select Product"></multiselect>

                    <div v-if="errors && errors.manufactured"
                         class="text-danger">{{ errors.manufactured[0] }}</div>
                </div>

                <div class="form-group col-lg-2 col-md-6 col-12">
                    <input type="text" v-model="item.amount" class="form-control" id="amount"
                           placeholder="100.60">
                    <div v-if="errors && errors.amount" class="text-danger">{{
                        errors.amount[0]
                      }}</div>
                </div>

                <div class="form-group col-lg-2 col-md-6 col-12">
                    <input type="text" v-model="item.quantity"
                           class="form-control "
                           id="quantity" placeholder="e.g 1">
                        <div v-if="errors && errors.quantity"
                             class="text-danger">{{ errors.quantity[0] }}</div>
                </div>
                <div class="form-group col-lg-1 col-md-6 col-12">
                    <button @click="sliceItems(index)"
                            class="btn btn-sm btn-outline-danger">X</button>
                </div>
            </div>
            <div class="form-group">
                
                <table class="table">
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <th>SUB TOTAL</th>
                        <td>Discount: <input type="text" v-model="fields.discount"
                                             id="discount" placeholder="e.g. 10"/>
                                    <select v-model="fields.disc_type" @change="setDiscount">
                                        <option value="amount">Amount</option>
                                        <option value="%">%</option>
                                    </select>
                        </td>
                        <td>Rate: {{
                            fields.items.reduce(
                                (acc, item) => acc + parseInt(item.amount * item.quantity), 0)
                          }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <th>TOTAL</th>
                        <th>{{ fields.discount }}</th>
                        <th>{{
                            fields.items.reduce(
                                (acc, item) => acc + parseInt(item.amount * item.quantity), 0)
                            - fields.discount
                          }}</th>
                    </tr>
                </table>
                <div class="form-group col-lg-12 col-md-6 col-12">
                    <label>Notes: </label>
                    <textarea class="form-control" v-model="fields.notes"></textarea>
                    <div v-if="errors && errors.notes" class="text-danger">{{
                        errors.notes[0]
                      }}</div>
                </div>
                <div class="form-group col-lg-12 col-md-6 col-12">
                    <label>Terms & Conditions: </label>
                    <textarea class="form-control" v-model="fields.toc"></textarea>
                    <div v-if="errors && errors.toc" class="text-danger">{{ errors.toc[0] }}</div>
                </div>
            </div>
        </span>
    <div class="form-row" :hidden="!isNext">
      <div class="form-group col-lg-4 col-md-4 col-6">
        <button type="button" @click="loadPrev" class="btn btn-warning pull-right">{{ '< Back' }}
        </button>
      </div>
      <div class="form-group col-lg-4 col-md-4 col-6">
      </div>
      <div class="form-group col-lg-4 col-md-4 col-6">

        <button @click="beforeSubmit('saved')" type="submit" :disabled="busyWriting"
                class="btn btn-primary float-right">
          <span v-if="busyWriting">Submitting..</span>
          <span v-else>Save</span>
        </button>


      </div>


    </div>
  </form>
</template>
<script>
import FormMixin from '../mixins/FormMixin';

export default {

  mixins: [FormMixin],
  data() {
    return {
      farmers: [],
      action: '/cooperative/sales/pos/add',
      text: 'Invoice created successfully',
      busyWriting: false,
      customers: [],
      products: [],
      manufactureds: [],
      whatToSell: [
        {'id': 1, 'name': "Collections"},
        {'id': 2, 'name': "Manufactured Products"}
      ],
      buyers: [
        {id: 1, who: 'Farmer'},
        {id: 2, who: 'External Customer'},
      ],
      redirect: 'redir',
      isHiddenCustomer: true,
      isHiddenFarmer: true,
      isHiddenCollected: true,
      isHiddenManufactured: true,
      isNext: false,
      available: '',
      fields: {
        farmer: '',
        customer: '',
        type: 'sale',
        product: '',
        items: [],
        manufactured: '',
        mfgObj: '',
        whatObj: '',
        productObj: '',
        amount: '',
        discount: '',
        quantity: '',
        what_to_sell: '',
        what: '',
        save_type: '',
        due_date: ''
      }
    }

  },
  //watch for changes on completed boolean
  watch: {
    completed: function (value) {
      this.completed = false;
    }
  },
  //disable comonents when loading
  computed: {
    isDisabled() {
      return this.busyWriting;
    },
  },
  methods: {
    //get farmers
    getFarmers() {
      axios.get('/cooperative/farmers/all').then(response => {
        this.farmers = response.data;
      }).catch(err => {
        // console.log(err)
      });
    },
    //get customers
    getCustomers() {
      axios.get('/cooperative/customer/get').then(response => {
        this.customers = response.data;
      }).catch(err => {
        // console.log(err)
      });
    },
    //get produces
    getProduces() {
      axios.get('/cooperative/sales/collected-products').then(response => {
        this.products = response.data;

      }).catch(err => {
        // console.log(err)
      });
    },
    //get productions
    getProductions() {
      axios.get('/cooperative/sales/manufactured-products').then(response => {
        this.manufactureds = response.data;
      }).catch(err => {
        console.log(err)
      });
    },
    customLabel({final_product, final_selling_price}) {
      return `${final_product.name} @${final_selling_price}`
    },
    customLabel1({name, sale_price}) {
      return `${name} @${sale_price}`
    },
    customLabelName({first_name, other_names, farmer}) {
      return `${first_name} ${other_names} - ${farmer.member_no}`
    },
    onChange(value) {
      if (value.id == 1) {
        this.isHiddenFarmer = false;
        this.isHiddenCustomer = true
      } else {
        this.isHiddenFarmer = true
        this.isHiddenCustomer = false
      }
    },
    onChangeFarmer(value) {
      this.fields.farmer = value.farmer.id;
    },
    onChangeCustomer(value) {
      this.fields.customer = value.id;
    },
    onChangeCollected(value) {
      this.fields.product = value.collections[0].id;
      this.fields.productName = value.name;
      this.available = value.collections.reduce(
          (acc, val) => acc + parseInt(val.available_quantity), 0)
    },
    onChangeManufactured(value) {
      this.fields.manufactured = value.id;
      this.fields.productName = value.final_product.name;
      this.available = value.available_quantity
    },
    //select what to sell
    onChangeWhat(value) {
      if (value.id == 1) {
        this.isHiddenCollected = false;
        this.isHiddenManufactured = true;
      } else {
        this.isHiddenCollected = true;
        this.isHiddenManufactured = false;
      }
      this.fields.what_to_sell = value.id;
      this.fields.what = value;
    },
    //load items page
    loadNext() {
      this.isNext = true;
    },
    loadPrev() {
      this.isNext = false;
    },
    pushItems() {
      if (this.fields.amount < 1) {
        alert("Please enter details");
        return;
      }
      if (this.fields.quantity > this.available) {
        alert("Alert! Quantity available is: " + this.available + '!')
        return;
      }
      this.fields.items.push({
        'id': this.fields.items.length + 1,
        'product': this.fields.product,
        'manufactured': this.fields.manufactured,
        'productName': this.fields.productName,
        'amount': this.fields.amount,
        'quantity': this.fields.quantity,
        'what_to_sell': this.fields.what_to_sell,
        'discount': this.fields.discount,
        'what': this.fields.what,
      })
      this.fields.productObj = null,
          this.fields.mfgObj = {},
          this.fields.amount = null,
          this.fields.quantity = null,
          this.fields.whatObj = {}
      // this.fields.discount =  null

    },
    beforeSubmit(val) {
      this.fields.save_type = val;
    },
    sliceItems(index) {
      this.fields.items.splice(index, 1);
    },
    setDiscount(val) {
      let dst = this.fields.discount
      if (val.target.value == '%') {
        let ds = (this.fields.discount / 100) * this.fields.items.reduce(
            (acc, item) => acc + parseInt(item.amount * item.quantity), 0)
        this.fields.discount = ds
      } else {
        this.fields.discount = dst
      }
    }

  },
  mounted() {
    this.getFarmers();
    this.getCustomers();
    this.getProduces();
    this.getProductions();
  }

}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
