export default {
    data() {
        return {
            fields: {},
            data: {},
            errors: {},
            loaded: true,
            action: "", //action url
            text: "",
            redirect: "",
            completed: false,
            busyWriting: false
        };
    },

    methods: {
        
        //add /edit
        submit() {
            this.busyWriting = true; //
            if (this.loaded) {
                this.loaded = false;
                this.errors = {};
                // get urls and success messages

                axios
                    .post(this.action, this.fields)
                    .then(response => {
                        // this.fields = {}; //Clear input fields.
                        this.loaded = true;
                        this.success = true;
                        this.busyWriting = false; //
                        //sweet alert with redirect
                        var mtext = this.text;
                        var back = this.redirect;
                        this.completed = true;
                        this.fields = {}; //Clear input fields.
                        /* swal({
                            title: "Success",
                            text: mtext,
                            icon: "success",
                            timer: 1000,
                            type: "success",
                            buttons: {
                                confirm: {
                                    text: "Ok..",
                                    visible: true,
                                    className: "btn btn-info"
                                }
                            }
                        }).then(function() {
                            if(this.redirect != '')
                            {
                                window.location.href  = this.redirect;
                            }

                            swal.close();
                        });*/
                        if(this.redirect != '')
                        {
                            if(this.redirect == 'redir') {
                                window.location.href  = '/cooperative/sales/pos/'+response.data.id+'/sale-items';
                            } else if(this.redirect == 'redirection') {
                                window.location.href  = '/cooperative/sales/pos/'+response.data.id+'/sale-quotation';
                            } else {
                                window.location.href = this.redirect
                            }
                        }
                        
                        // swal.close();
                    })
                    .catch(error => {
                        this.loaded = true;
                        if (error.response.status === 422) {
                            this.busyWriting = false;
                            this.errors = error.response.data.errors || {};
                        } else if (error.response.status === 400) {
                            this.busyWriting = false;
                            this.errors = error.response.data;
                            alert(this.errors)
                        }
                        else {
                            this.errors = error.response.data.errors || {};
                            this.busyWriting = false; //
                            
                            // end fail
                        }
                        this.busyWriting = false;
                        if(this.redirect != '')
                        {
                            if(this.redirect == 'redir') {
                                window.location.href  = '/cooperative/sales/pos/'+response.data.id+'/sale-items';
                            } else if(this.redirect == 'redirection') {
                                window.location.href  = '/cooperative/sales/pos/'+response.data.id+'/sale-quotation';
                            } else {
                                window.location.href = this.redirect
                            }
                        }
                    });
            }
        }
    }
};
