<style>
    .v-select {
        margin-bottom: 5px;
    }

    .v-select.open .dropdown-toggle {
        border-bottom: 1px solid #ccc;
    }

    .v-select .dropdown-toggle {
        padding: 0px;
        height: 25px;
    }

    .v-select input[type=search],
    .v-select input[type=search]:focus {
        margin: 0px;
    }

    .v-select .vs__selected-options {
        overflow: hidden;
        flex-wrap: nowrap;
    }

    .v-select .selected-tag {
        margin: 2px 0px;
        white-space: nowrap;
        position: absolute;
        left: 0px;
    }

    .v-select .vs__actions {
        margin-top: -5px;
    }

    .v-select .dropdown-menu {
        width: auto;
        overflow-y: auto;
    }
    #branchDropdown .vs__actions button{
		display:none;
	}
	#branchDropdown .vs__actions .open-indicator{
		height:15px;
		margin-top:7px;
	}

	.modal-mask {
		position: fixed;
		z-index: 9998;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, .5);
		display: table;
		transition: opacity .3s ease;
	}
	.modal-wrapper {
		display: table-cell;
		vertical-align: middle;
	}

	.modal-container {
		width: 400px;
		margin: 0px auto;
		background-color: #fff;
		border-radius: 2px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
		transition: all .3s ease;
		font-family: Helvetica, Arial, sans-serif;
	}
	.modal-header {
		padding-bottom: 0 !important;
	}

	.modal-header h3 {
		margin-top: 0;
		color: #42b983;
	}

	.modal-body{
		overflow-y: auto !important;
		height: 300px !important;
		margin: -8px -14px -44px !important;
	}

	.modal-default-button {
		float: right;
	}

	.modal-enter {
		opacity: 0;
	}

	.modal-leave-active {
		opacity: 0;
	}

	.modal-enter .modal-container,
	.modal-leave-active .modal-container {
		-webkit-transform: scale(1.1);
		transform: scale(1.1);
	}

	.modal-footer {
		padding-top: 14px !important;
		margin-top: 30px !important;
	}
</style>
<div id="damages">
    <div class="row" style="margin-top: 15px;">
        <div class="col-md-8">
            <form class="form-horizontal" @submit.prevent="addDamage">
                <div class="form-group">
                    <label class="col-sm-6 control-label no-padding-right"> Code </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-5">
                        <input type="text" placeholder="Code" class="form-control" v-model="damage.Damage_InvoiceNo" required readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-6 control-label no-padding-right"> Date </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-5">
                        <input type="date" placeholder="Date" class="form-control" v-model="damage.Damage_Date" required />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-6 control-label no-padding-right"> Product </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-5">
                        <v-select v-bind:options="products" label="display_text" v-model="selectedProduct" placeholder="Select Product" v-on:input="productOnChange"></v-select>
                    </div>
                </div>

                <div class="form-group" style="display: none;" :style="{display: serials.length > 0 ? '' : 'none'}">
                    <label class="col-sm-6 control-label no-padding-right"> Serial </label>                    
                    <label class="col-sm-1 control-label no-padding-right">:</label>

                    <div class="col-sm-4" style="padding-right:15px">
                        <v-select v-bind:options="serials" v-model="serial" label="ps_serial_number" placeholder="Serial No"></v-select>
                    </div>
                    <div class="col-sm-1" style="padding: 0;">
                        <button type="button" id="show-modal" @click="serialShowModal" style="background: rgb(195 36 36 / 83%); color: white; border: none; font-size: 15px; height: 26px;width:28px; margin-left: -10px;margin-right:2px"><i class="fa fa-plus"></i></button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-6 control-label no-padding-right"> Damage Quantity </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-5">
                        <input type="number" placeholder="Quantity" class="form-control" v-model="damage.DamageDetails_DamageQuantity" required v-on:input="calculateTotal" v-bind:disabled="serials.length ? true : false"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-6 control-label no-padding-right"> Damage Rate </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-5">
                        <input type="number" placeholder="Rate" class="form-control" v-model="damage.damage_rate" required v-on:input="calculateTotal" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-6 control-label no-padding-right"> Damage Amount </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-5">
                        <input type="number" placeholder="Amount" class="form-control" v-model="damage.damage_amount" required disabled />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-6 control-label no-padding-right"> Description </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-5">
                        <textarea class="form-control" placeholder="Description" v-model="damage.Damage_Description"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-6 control-label no-padding-right"></label>
                    <label class="col-sm-1 control-label no-padding-right"></label>
                    <div class="col-sm-5">
                        <button type="submit" class="btn btn-sm btn-success">
                            Submit
                            <i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <h1 style="display: none;" v-bind:style="{display: productStock !== '' ? '' : 'none'}">Stock : {{productStock}}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 form-inline">
            <div class="form-group">
                <label for="filter" class="sr-only">Filter</label>
                <input type="text" class="form-control" v-model="filter" placeholder="Filter">
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <datatable :columns="columns" :data="damages" :filter-by="filter">
                    <template scope="{ row }">
                        <tr>
                            <td>{{ row.Damage_InvoiceNo }}</td>
                            <td>{{ row.Damage_Date }}</td>
                            <td>{{ row.Product_Code }}</td>
                            <td>{{ row.Product_Name }}</td>
                            <td>{{ row.DamageDetails_DamageQuantity }}</td>
                            <td>{{ row.damage_rate }}</td>
                            <td>{{ row.damage_amount }}</td>
                            <td>{{ row.Damage_Description }}</td>
                            <td>
                                <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                    <!-- <button type="button" class="button edit" @click="editDamage(row)">
                                        <i class="fa fa-pencil"></i>
                                    </button> -->
                                    <button type="button" class="button" @click="deleteDamage(row.Damage_SlNo)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                    </template>
                </datatable>
                <datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
            </div>
        </div>
    </div>
    <!-- sale product serial modal -->
	<div style="display:none" id="serial-modal" v-if="" v-bind:style="{display:serialModalStatus?'block':'none'}">
		<transition name="modal">
			<div class="modal-mask">
				<div class="modal-wrapper">
					<div class="modal-container">
						<div class="modal-header">
							<slot name="header">
								<h3>Serial Number Add</h3>
							</slot>
						</div>

						<div class="modal-body">
							<slot name="body">
								<form @submit.prevent="addSerialNumber">
									<div class="form-group">
										<label for="serial" class="col-sm-3">Start Serial</label>
										<div class="col-sm-9" style="display: flex; margin-bottom: 5px;">
											<input type="text" autocomplete="off" ref="serialnumberadd" v-model="serialCart.range" class="form-control" placeholder="Please Enter Serial Number" style="height: 30px;" required />
										</div>
									</div>
									<div class="form-group">
										<label for="quantity" class="col-sm-3">Range</label>
										<div class="col-sm-6">
											<input type="number" autocomplete="off" min="0" v-model="serialCart.quantity" class="form-control" required>
										</div>
										<div class="col-sm-2">
											<input type="submit" class="btn btn-sm primary" style="border: none; font-size: 13px; line-height: 0.38; background-color: #42b983 !important;height: 26px;width:76px;" value="Add">
										</div>
									</div>
								</form>
							</slot>
							<table class="table">
								<thead>
									<tr>
										<th scope="col">SL</th>
										<th scope="col">Serial</th>
										<th scope="col">Product</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
	
									<tr v-for="(product, sl) in sequences">
										<th scope="row">{{ sl+1 }}</th>
										<td>{{ product.ps_serial_number }}</td>
										<td>{{ product.Product_Name }}</td>
										<td @click="removeSerialItem(product.ps_serial_number)"> <span class="badge badge-danger badge-pill" style="cursor:pointer"><i class="fa fa-times"></i></td>
									</tr>
	
								</tbody>
							</table>
						</div>
						<div class="modal-footer">
							<slot name="footer">
								<button class="modal-default-button" @click="serialHideModal" style="background: #59b901;border: none;font-size: 18px;color: white;">
									OK
								</button>
								<button class="modal-default-button" @click="serialHideModal" style="background: rgb(255, 255, 255);border: none;font-size: 18px;color: #de0000;margin-right: 6px;">
									Close
								</button>
							</slot>
						</div>
					</div>
				</div>
			</div>
		</transition>
	</div>
	<!-- purchase product serial modal -->
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#damages',
        data() {
            return {
                damage: {
                    Damage_SlNo: 0,
                    Damage_InvoiceNo: '<?php echo $damageCode; ?>',
                    Damage_Date: moment().format('YYYY-MM-DD'),
                    Damage_Description: '',
                    Product_SlNo: '',
                    ps_id: '',
                    DamageDetails_DamageQuantity: '',
                    damage_rate: '',
                    damage_amount: 0,
                },
                products: [],
                selectedProduct: null,
                productStock: '',
                damages: [],
				serials: [],
				serial: null,
				serialModalStatus: false,
                serialCart: {
					range: '',
					quantity: 0
				},
				sequences: [],

                columns: [{
                        label: 'Code',
                        field: 'Damage_InvoiceNo',
                        align: 'center',
                        filterable: false
                    },
                    {
                        label: 'Date',
                        field: 'Damage_Date',
                        align: 'center'
                    },
                    {
                        label: 'Product Code',
                        field: 'Product_Code',
                        align: 'center'
                    },
                    {
                        label: 'Product Name',
                        field: 'Product_Name',
                        align: 'center'
                    },
                    {
                        label: 'Quantity',
                        field: 'DamageDetails_DamageQuantity',
                        align: 'center'
                    },
                    {
                        label: 'Damage Rate',
                        field: 'damage_rate',
                        align: 'center'
                    },
                    {
                        label: 'Damage Amount',
                        field: 'damage_amount',
                        align: 'center'
                    },
                    {
                        label: 'Description',
                        field: 'Damage_Description',
                        align: 'center'
                    },
                    {
                        label: 'Action',
                        align: 'center',
                        filterable: false
                    }
                ],
                page: 1,
                per_page: 10,
                filter: ''
            }
        },
		watch: {
			async serial(serial) {
				if(serial == undefined) return;
				this.selectedProduct = this.products.find(item => item.Product_SlNo == serial.ps_prod_id)
				this.psId = serial.ps_id;
				this.psSerialNumber = serial.ps_serial_number;
				this.damage.DamageDetails_DamageQuantity = 1;
                let damage_amount = parseFloat(this.damage.damage_rate) * parseFloat(this.damage.DamageDetails_DamageQuantity);
                this.damage.damage_amount = isNaN(damage_amount) ? 0 : damage_amount;
				this.productStock = await axios.post('/get_product_stock', {productId: serial.ps_prod_id})
				.then(res => {
						return res.data;
					})
				this.productStockText = this.productStock > 0 ? "Available Stock" : "Stock Unavailable";
			}
		},
        created() {
            this.getProducts();
            this.getDamages();
        },
        methods: {
            // async productOnChange() {
            //     if ((this.selectedProduct.Product_SlNo != '' || this.selectedProduct.Product_SlNo != 0)) {
            //         this.damage.damage_rate = this.selectedProduct.Product_Purchase_Rate;

            //         let damage_amount = parseFloat(this.damage.damage_rate) * parseFloat(this.damage.DamageDetails_DamageQuantity);
            //         this.damage.damage_amount = isNaN(damage_amount) ? 0 : damage_amount;

            //         this.productStock = await axios.post('/get_product_stock', {productId: this.selectedProduct.Product_SlNo}).then(res => {
            //             return res.data;
            //         })
            //     }
            // },

            async productOnChange(){
				if ((this.selectedProduct.Product_SlNo != '' || this.selectedProduct.Product_SlNo != 0)){
                    this.damage.damage_rate = this.selectedProduct.Product_Purchase_Rate;

                    let damage_amount = parseFloat(this.damage.damage_rate) * parseFloat(this.damage.DamageDetails_DamageQuantity);
                    this.damage.damage_amount = isNaN(damage_amount) ? 0 : damage_amount;

					this.productStock = await axios.post('/get_product_stock', {productId: this.selectedProduct.Product_SlNo}).then(res => {
						return res.data;
					})
					
					await axios.post('/get_Serial_By_Prod',{ prod_id: this.selectedProduct.Product_SlNo}).then(res => {
						this.serials = res.data;
					})

                    console.log(this.serials);
				}
			},

            getProducts() {
                axios.post('/get_products', {
                    isService: 'false'
                }).then(res => {
                    this.products = res.data;
                })
            },
            addDamage() {
                if (this.selectedProduct == null) {
                    alert('Select product');
                    return;
                }

                if (this.damage.DamageDetails_DamageQuantity > this.productStock) {
                    alert('Stock unavailable');
                    return;
                }

                this.damage.Product_SlNo = this.selectedProduct.Product_SlNo;
                this.damage.ps_id = this.serial.ps_id;

                let url = '/add_damage';
                if (this.damage.Damage_SlNo != 0) {
                    url = '/update_damage'
                }
                axios.post(url, this.damage).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.resetForm();
                        this.damage.Damage_InvoiceNo = r.newCode;
                        this.getDamages();
                    }
                })
            },

            editDamage(damage) {
                let keys = Object.keys(this.damage);
                keys.forEach(key => this.damage[key] = damage[key]);

                this.selectedProduct = {
                    Product_SlNo: damage.Product_SlNo,
                    display_text: `${damage.Product_Name} - ${damage.Product_Code}`
                }
            },

            calculateTotal() {
                let damage_amount = parseFloat(this.damage.damage_rate) * parseFloat(this.damage.DamageDetails_DamageQuantity);

                this.damage.damage_amount = isNaN(damage_amount) ? 0 : damage_amount;
            },

            deleteDamage(damageId) {
                let deleteConfirm = confirm('Are you sure?');
                if (deleteConfirm == false) {
                    return;
                }
                axios.post('/delete_damage', {
                    damageId: damageId
                }).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getDamages();
                    }
                })
            },

            getDamages() {
                axios.get('/get_damages').then(res => {
                    this.damages = res.data;
                })
            },

            resetForm() {
                this.damage.Damage_SlNo = '';
                this.damage.Damage_Description = '';
                this.damage.Product_SlNo = '';
                this.damage.DamageDetails_DamageQuantity = '';
                this.damage.damage_rate = '';
                this.damage.damage_amount = 0;
                this.selectedProduct = null;
                this.productStock = '';
            },
            serialShowModal() {
				this.serialModalStatus = true;
			},
			serialHideModal() {
				this.serialModalStatus = false;
			},
			async addSerialNumber() {
				let serial = this.serials.find(item => item.ps_serial_number == this.serialCart.range)
				if(this.serialCart.quantity == 0 || this.serialCart.quantity == '') {
					alert('Serial range is required !');
					return;
				}

				if(serial == undefined) {
					alert('Your request serial not match !');
					return;
				} 

				let data = {
					id: serial.ps_id,
					invoice: serial.ps_purchase_inv,
					productId: serial.ps_prod_id,
					limit: this.serialCart.quantity
				}
				await axios.post('/get_secquence', data)
				.then(res => {
					this.sequences = res.data;
					this.serialCart.range = '';
					this.serialCart.quantity = 0;
				})				
			},
			removeSerialItem(serial) {
				this.sequences.splice(serial, 1)
			}
        }
    })
</script>