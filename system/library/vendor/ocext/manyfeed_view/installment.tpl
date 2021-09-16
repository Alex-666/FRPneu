<tr>
                <td><?php echo $text_setting_installment; ?></td>
                <td>
                    <select name="setting[installment][status]" onchange="if(this.value!=0){ $('#template_setting_installment<?php echo $setting_id ?>').show() }else{ $('#template_setting_installment<?php echo $setting_id ?>').hide() }" class="form-control" >
                        <?php $setting_delivery_options_css = "display:none"; ?>
                        <?php if(isset($setting['installment']['status']) && $setting['installment']['status'] == 'installment'){ ?>
                            <?php $setting_delivery_options_css = "display:block"; ?>
                            <option selected=""  value="installment"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="installment"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                    <div id="template_setting_installment<?php echo $setting_id ?>" style="margin-top: 5px; <?php echo $setting_delivery_options_css ?>">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <td colspan="2" style="text-align: center">Tags</td>
                                <td colspan="2" style="text-align: center">Apply if</td>
                            </tr>
                            <tr>
                            <td>Months*</td>
                            <td>Amount</td>
                            <td>Entity id</td>
                            <td>Price range<br><small><b style="color:red">10-20</b>, or <b style="color:red">>=20</b>, or <b style="color:red"><=5.3</b>, or <b style="color:red">>12.05</b></small></td>
                            </tr>
                        </thead>
                        <?php for($i=0;$i<5;$i++){ ?>
                        <?php
                        $months = '';
                        $amount = '';
                        if( isset($setting['installment'][$i]['months']) ){
                            $months = $setting['installment'][$i]['months'];
                        }
                        if( isset($setting['installment'][$i]['amount']) ){
                            $amount = $setting['installment'][$i]['amount'];
                        }
                        ?>
                        <tr>
                            <td><input type="text" value="<?php echo $months ?>" name="setting[installment][<?php echo $i ?>][months]" class="form-control" /></td>
                            <td><input type="text" value="<?php echo $amount ?>" name="setting[installment][<?php echo $i ?>][amount]" class="form-control" /></td>
                            <td>
                                
                                <div>
                                    <?php
                                    
                                    $field = 'product_ids_only';
                                    
                                    $setting_field = 'installment';
                                    
                                    ${$field} = '';
                                    
                                    if( isset($setting[$setting_field][$i][$field]) ){
                                        ${$field} = $setting[$setting_field][$i][$field];
                                    }
                                    
                                    ?>
                                    <div class="small_text"><?php echo ${'text_'.$field};  ?></div>
                                    <input  type="text" class="form-control" placeholder="" value="<?php echo ${$field}; ?>" name="setting[<?php echo $setting_field ?>][<?php echo $i ?>][<?php echo $field; ?>]" /> 
                                </div>
                                
                                <div>
                                    <?php
                                    
                                    $field = 'manufacturer_ids_only';
                                    
                                    $setting_field = 'installment';
                                    
                                    ${$field} = '';
                                    
                                    if( isset($setting[$setting_field][$i][$field]) ){
                                        ${$field} = $setting[$setting_field][$i][$field];
                                    }
                                    
                                    ?>
                                    <div class="small_text"><?php echo ${'text_'.$field};  ?></div>
                                    <input  type="text" class="form-control" placeholder="" value="<?php echo ${$field}; ?>" name="setting[<?php echo $setting_field ?>][<?php echo $i ?>][<?php echo $field; ?>]" /> 
                                </div>
                                
                                <div>
                                    <?php
                                    
                                    $field = 'category_ids_only';
                                    
                                    $setting_field = 'installment';
                                    
                                    ${$field} = '';
                                    
                                    if( isset($setting[$setting_field][$i][$field]) ){
                                        ${$field} = $setting[$setting_field][$i][$field];
                                    }
                                    
                                    ?>
                                    <div class="small_text"><?php echo ${'text_'.$field};  ?></div>
                                    <input  type="text" class="form-control" placeholder="" value="<?php echo ${$field}; ?>" name="setting[<?php echo $setting_field ?>][<?php echo $i ?>][<?php  echo $field; ?>]" /> 
                                </div>
                                
                            </td>
                            
                            <td>
                                
                                <div>
                                    <?php
                                    
                                    $field = 'price_range';
                                    
                                    $setting_field = 'installment';
                                    
                                    ${$field} = '';
                                    
                                    if( isset($setting[$setting_field][$i][$field]) ){
                                        ${$field} = $setting[$setting_field][$i][$field];
                                    }
                                    
                                    ?>
                                    <input  type="text" class="form-control" placeholder="" value="<?php echo ${$field}; ?>" name="setting[<?php echo $setting_field ?>][<?php echo $i ?>][<?php echo $field; ?>]" /> 
                                </div>
                                
                            </td>
                            
                        </tr>
                        <?php } ?> 
                    </table>
                    </div>
                </td>
            </tr>