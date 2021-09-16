<tr>
                <td><?php echo $text_setting_tax; ?></td>
                <?php $setting_field = 'tax'; ?>
                <td>
                    <select name="setting[<?php echo $setting_field ?>][status]" onchange="if(this.value!=0){ $('#template_setting_<?php echo $setting_field ?><?php echo $setting_id ?>').show() }else{ $('#template_setting_<?php echo $setting_field ?><?php echo $setting_id ?>').hide() }" class="form-control" >
                        <?php $setting_delivery_options_css = "display:none"; ?>
                        <?php if(isset($setting[$setting_field]['status']) && $setting[$setting_field]['status'] == $setting_field){ ?>
                            <?php $setting_delivery_options_css = "display:block"; ?>
                            <option selected=""  value="<?php echo $setting_field ?>"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field ?>"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                    <div id="template_setting_<?php echo $setting_field ?><?php echo $setting_id ?>" style="margin-top: 5px; <?php echo $setting_delivery_options_css ?>">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <td colspan="4" style="text-align: center">Tags</td>
                                <td colspan="2" style="text-align: center">Apply if</td>
                            </tr>
                            <tr>
                                <td>country</td>
                                <td>region</td>
                                <td>rate</td>
                                <td>tax_ship</td>
                                <td>Entity id</td>
                                <td>Price range<br><small><b style="color:red">10-20</b>, or <b style="color:red">>=20</b>, or <b style="color:red"><=5.3</b>, or <b style="color:red">>12.05</b></small></td>
                            </tr>
                        </thead>
                        <?php for($i=0;$i<5;$i++){ ?>
                        <?php
                        $country = '';
                        $region = '';
                        $rate = '';
                        $tax_ship = '';
                        if( isset($setting[$setting_field][$i]['country']) ){
                            $country = $setting[$setting_field][$i]['country'];
                        }
                        if( isset($setting[$setting_field][$i]['region']) ){
                            $region = $setting[$setting_field][$i]['region'];
                        }
                        if( isset($setting[$setting_field][$i]['rate']) ){
                            $rate = $setting[$setting_field][$i]['rate'];
                        }
                        if( isset($setting[$setting_field][$i]['tax_ship']) ){
                            $tax_ship = $setting[$setting_field][$i]['tax_ship'];
                        }
                        ?>
                        <tr>
                            <td><input type="text" value="<?php echo $country ?>" name="setting[<?php echo $setting_field ?>][<?php echo $i ?>][country]" class="form-control" /></td>
                            <td><input type="text" value="<?php echo $region ?>" name="setting[<?php echo $setting_field ?>][<?php echo $i ?>][region]" class="form-control" /></td>
                            <td><input type="text" value="<?php echo $rate ?>" name="setting[<?php echo $setting_field ?>][<?php echo $i ?>][rate]" class="form-control" /></td>
                            <td><input type="text" value="<?php echo $tax_ship ?>" name="setting[<?php echo $setting_field ?>][<?php echo $i ?>][tax_ship]" class="form-control" /></td>
                            <td>
                                
                                <div>
                                    <?php
                                    
                                    $field = 'product_ids_only';
                                    
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