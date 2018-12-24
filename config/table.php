<?php
/**
 * Created by PhpStorm.
 * User: duc_hung
 * Date: 20-Nov-18
 * Time: 1:33 PM
 */

$config_table['export_data_csv'] = array(
    'customer' => array(
        'table_related'    => array(
            'contract'  => array(
                'table_related'    => array(
                    'contract_order' => array(
                        'table_related'    => array(
                            'order_detail' => array(
                                'table_related'    => array(
                                    'amazon_api_log' => array(
                                        'table_related'    => array(),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                        ),
                                    ),
                                    'apply_claim_fee' => array(
                                        'table_related'    => array(
                                            'apply_cancel' => array(
                                                'table_related'    => array(),
                                                'column'    => array(
                                                    'contract'  => 'contract_id',
                                                    'apply_claim_fee'  => 'apply_claim_fee_id'
                                                ),
                                            ),
                                        ),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                            'contract'  => 'contract_id',
                                        ),
                                    ),
                                    'coupon_acquisition' => array(
                                        'table_related'    => array(
                                        ),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                            'contract'  => 'contract_id'
                                        ),
                                    ),
                                    'coupon_contract_detail' => array(
                                        'table_related'    => array(),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                            'contract'  => 'contract_id'
                                        ),
                                    ),
                                    'lottery_campaign' => array(
                                        'table_related'    => array(),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                            'contract'  => 'contract_id'
                                        ),
                                    ),
                                    'order_acquisition' => array(
                                        'table_related'    => array(),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                        ),
                                    ),
                                    'order_delivery' => array(
                                        'table_related'    => array(),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                        ),
                                    ),
                                    'order_product' => array(
                                        'table_related'    => array(),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                        ),
                                    ),
                                    'order_settlement' => array(
                                        'table_related'    => array(
                                            'apply_cancel_settlement' => array(
                                                'table_related'    => array(),
                                                'column'    => array(
                                                    'order_settlement'  => 'order_settlement_id',
                                                ),
                                            ),
                                            'apply_retry_settlement' => array(
                                                'table_related'    => array(),
                                                'column'    => array(
                                                    'order_settlement'  => 'order_settlement_id',
                                                ),
                                            ),
                                            'order_settlement_detail' => array(
                                                'table_related'    => array(),
                                                'column'    => array(
                                                    'order_product'  => 'order_product_id',
                                                    'order_settlement'  => 'order_settlement_id',
                                                ),
                                            ),
                                            'settlement_download_detail_log' => array(
                                                'table_related'    => array(),
                                                'column'    => array(
                                                    'order_settlement'  => 'order_settlement_id',
                                                ),
                                            ),
                                            'settlement_paygent_detail_log' => array(
                                                'table_related'    => array(),
                                                'column'    => array(
                                                    'order_settlement'  => 'order_settlement_id',
                                                ),
                                            ),


                                        ),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                        ),
                                    ),
                                    'receipt_download_log' => array(
                                        'table_related'    => array(),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                        ),
                                    ),
                                    'settlement_np_detail_log' => array(
                                        'table_related'    => array(),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                        ),
                                    ),
                                    'settlement_sbps_detail_log_purchase' => array(
                                        'table_related'    => array(
                                            'settlement_sbps_detail_log_purchase_transaction' => array(
                                                'table_related'    => array(),
                                                'column'    => array(
                                                    'order_detail'  => 'order_detail_id',
                                                    'settlement_sbps_detail_log_purchase'  => 'settlement_sbps_detail_log_purchase_id',
                                                ),
                                            ),
                                        ),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                        ),
                                    ),

                                ),
                                'column'    => array(
                                    'contract_order'  => 'contract_order_id'
                                ),
                            ),
                            'data_order' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract_order'  => 'contract_order_id'
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),

                    'contract_direct_debit_mizuho' => array(
                        'table_related'    => array(
                            'contract_direct_debit_fee' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'order_detail'  => 'order_detail_id',
                                    'contract_direct_debit_mizuho'  => 'contract_direct_debit_mizuho_id',
                                ),
                            ),
                            'settlement_direct_debit_mizuho_log' => array(
                                'table_related'    => array(
                                    'settlement_direct_debit_mizuho_detail_log' => array(
                                        'table_related'    => array(),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                            'settlement_direct_debit_mizuho_log'  => 'settlement_direct_debit_mizuho_log_id',
                                        ),
                                    ),
                                ),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_direct_debit_mizuho'  => 'contract_direct_debit_mizuho_id',
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_paygent' => array(
                        'table_related'    => array(
                            'contract_paygent_history' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_paygent'  => 'contract_paygent_id',
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'apply_inapplicable_transfer' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'apply_update_acquisition' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contact_log' => array(
                        'table_related'    => array(
                            'contact_log_upload_file' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contact_log'  => 'contact_log_id'
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id',
                            'customer'  => 'customer_id',
                        ),
                    ),
                    'contract_acquisition' => array(
                        'table_related'    => array(
                            'contract_acquisition_history' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_acquisition'  => 'contract_acquisition_id'
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_amazon' => array(
                        'table_related'    => array(
                            'contract_amazon_history' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_amazon'  => 'contract_amazon_id'
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_bank_account' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_bill' => array(
                        'table_related'    => array(
                            'contract_bill_history' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_bill'  => 'contract_bill_id'
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_card_ivr_log' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_delivery' => array(
                        'table_related'    => array(
                            'contract_delivery_history' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_delivery'  => 'contract_delivery_id'
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_demand_status' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_direct_debit' => array(
                        'table_related'    => array(
                            'contract_direct_debit_history' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract'                  => 'contract_id',
                                    'contract_direct_debit'     => 'contract_direct_debit_id',
                                ),
                            ),

                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_discount' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_enecom' => array(
                        'table_related'    => array(
                            'contract_enecom_history' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_enecom'  => 'contract_enecom_id',
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_force_cancel_date' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_hash' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_history' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'customer'  => 'customer_id',
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_option_product' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_premium_safe_package' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_regist_route' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_retention' => array(
                        'table_related'    => array(
                            'contract_retention_history' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_retention'  => 'contract_retention_id',
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_ryfety' => array(
                        'table_related'    => array(
                            'settlement_ryfety_log' => array(
                                'table_related'    => array(
                                    'settlement_ryfety_log_detail' => array(
                                        'table_related'    => array(),
                                        'column'    => array(
                                            'order_detail'  => 'order_detail_id',
                                            'settlement_ryfety_log'  => 'settlement_ryfety_log_id',
                                        ),
                                    ),
                                ),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_ryfety'  => 'contract_ryfety_id',
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_ryfety_complete' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_sbps' => array(
                        'table_related'    => array(
                            'contract_sbps_history' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_sbps'  => 'contract_sbps_id',
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_server_log' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_service_hydrogen' => array(
                        'table_related'    => array(
                            'contract_service_hydrogen_history' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_service_hydrogen'  => 'contract_service_hydrogen_id',
                                ),
                            ),
                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_service_supplement' => array(
                        'table_related'    => array(
                            'contract_service_supplement_history' => array(
                                'table_related'    => array(),
                                'column'    => array(
                                    'contract'  => 'contract_id',
                                    'contract_service_supplement'  => 'contract_service_supplement_id',
                                ),
                            ),
                            'contract_service_supplement_detail' => array(
                                'table_related'    => array(
                                    'contract_service_supplement_detail_history' => array(
                                        'table_related'    => array(),
                                        'column'    => array(
                                            'contract_service_supplement'  => 'contract_service_supplement_id',
                                            'contract_service_supplement_detail'  => 'contract_service_supplement_detail_id',
                                        ),
                                    ),
                                ),
                                'column'    => array(
                                    'contract_service_supplement'  => 'contract_service_supplement_id',
                                ),
                            ),

                        ),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_setting_free' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_tag_allocation' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_temp_cancel_date' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'contract_temp_regist_route' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'email_validation_checked_result' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'fee_details_shot' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'fee_details_shot_ryfety' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                     'lottery' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'operation_log' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id',
                            'customer'  => 'customer_id',
                        ),
                    ),
                    'opt_login' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'push_information_contract' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'push_information_contract_id' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'questionnaire_electricity' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'questionnaire_insurance' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'report_customer_analyze_data' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'report_exclude_aso_order' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'settlement_bill_log' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                    'transfer_detail' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'contract'  => 'contract_id'
                        ),
                    ),
                ),
                'column'    => array(
                    'customer'  => 'customer_id'
                ),
            ),
            'coupon_contract' => array(
                'table_related'    => array(),
                'column'    => array(
                    'customer'  => 'customer_id'
                ),
            ),
            'customer_enecom' => array(
                'table_related'    => array(
                    'customer_enecom_history' => array(
                        'table_related'    => array(),
                        'column'    => array(
                            'customer'  => 'customer_id',
                            'customer_enecom'  => 'customer_enecom_id',
                        ),
                    ),
                ),
                'column'    => array(
                    'customer'  => 'customer_id'
                ),
            ),

            'customer_history' => array(
                'table_related'    => array(),
                'column'    => array(
                    'customer'  => 'customer_id'
                ),
            ),
            'customer_login_id_history' => array(
                'table_related'    => array(),
                'column'    => array(
                    'customer'  => 'customer_id'
                ),
            ),
            'push_information_customer' => array(
                'table_related'    => array(),
                'column'    => array(
                    'customer'  => 'customer_id'
                ),
            ),

        ),
    ),

);

