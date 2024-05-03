<template>
  <form @submit.prevent="submit" method="post">
    <div class="form-row">
      <div class="form-group col-lg-3 col-md-6 col-12">
        <label for="farmer_id">Farmer ID</label>
        <multiselect v-model="fields.farmer_id" :options="farmers"
                     :custom-label="customLabelFarmerNames" :searchable="true"
                     :close-on-select="true" :show-labels="false" @input="pushSupplies"
                     placeholder="Select Farmer"></multiselect>
        <div v-if="errors && errors.farmer_id" class="text-danger">{{ errors.farmer_id[0] }}</div>
      </div>

      <div class="form-group col-lg-3 col-md-6 col-12">
        <label for="product">Product</label>
        <multiselect v-model="fields.product" label="name" :options="farmerproducts"
                     :searchable="true"
                     :close-on-select="true" :show-labels="false"
                     placeholder="Select Product"></multiselect>
      </div>

      <div class="form-group col-lg-3 col-md-6 col-12">
        <label for="standard_id">Quality Standards</label>
        <multiselect v-model="fields.standard_id" :options="standards" label="name"
                     :searchable="true"
                     :close-on-select="true" :show-labels="false"
                     placeholder="Select Product Standard"></multiselect>
        <div v-if="errors && errors.standard_id" class="text-danger">{{
            errors.standard_id[0]
          }}
        </div>
      </div>

      <div class="form-group col-lg-3 col-md-6 col-12">
        <label for="quantity">Quantity</label>
        <input type="text" v-model="fields.quantity" class="form-control " id="quantity"
               placeholder="10.5" required>
        <div v-if="errors && errors.quantity" class="text-danger">{{ errors.quantity[0] }}</div>
      </div>

<!--      <div class="form-group col-lg-3 col-md-6 col-12">-->
<!--        <label for="agent">Agent</label>-->
<!--        <multiselect v-model="fields.agent" label="first_name" :options="agents" :searchable="true"-->
<!--                     :close-on-select="true" :show-labels="false"-->
<!--                     placeholder="Select Agent"></multiselect>-->
<!--      </div>-->

      <div class="form-group col-lg-3 col-md-6 col-12">
        <label for="agent">Collection Time</label>
        <multiselect v-model="fields.collection_time" label="name" :options="collection_times" :searchable="true"
                     :close-on-select="true" :show-labels="false"
                     placeholder="Select Time"></multiselect>
        <div v-if="errors && errors.collection_time" class="text-danger">{{ errors.collection_time[0] }}</div>
      </div>

      <div class="form-group col-lg-3 col-md-6 col-12">
        <label for="comments">Comments</label>
        <textarea type="text" v-model="fields.comments" class="form-control" id="comments"
                  placeholder="Description"></textarea>
        <div v-if="errors && errors.comments" class="text-danger">{{ errors.comments[0] }}</div>
      </div>

    </div>
    <div class="form-row">
      <div class="form-group col-lg-3 col-md-3 col-12">
        <label for=""></label>
        <button type="submit" :disabled="busyWriting" class="btn btn-primary btn-fw btn-block">
          <span v-if="busyWriting">Adding..</span>
          <span v-else>Add</span>
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
      action: '/cooperative/collection/add',
      text: 'Collection done successfully',
      busyWriting: false,
      farmerproducts: [],
      standards: [],
      agents: [],
      collection_times: [
          {'name': 'Morning', 'key': '1'},
        {'name':'Afternoon', 'key':'2'},
        {'name':'Evening', 'key':'3'}
      ],
      redirect: '?',
      fields: {
        farmer: ''
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
    }
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
    //get agents
    getAgents() {
      axios.get('/cooperative/agents/all').then(response => {
        this.agents = response.data;
      }).catch(err => {
        // console.log(err)
      });
    },

    //get agents
    getStdQualities() {
      axios.get('/cooperative/std-qualities').then(response => {
        this.standards = response.data;
      }).catch(err => {
        console.log(err)
      });
    },
    //push products to multiselect
    pushSupplies(value) {
      if (value) {
        this.farmerproducts = value.products;
        this.fields.farmer = value.farmer.id;
      } else {
        this.farmerproducts = [];
      }
    },
    customLabelFarmerNames({first_name, other_names, farmer}) {
      return `${farmer.member_no} - ${first_name} ${other_names}`
    }
  },
  mounted() {
    this.getFarmers();
    this.getAgents();
    this.getStdQualities();
  }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
