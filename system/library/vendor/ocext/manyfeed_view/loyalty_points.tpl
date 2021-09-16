<tr>
                <td><?php echo $text_setting_loyalty_points; ?></td>
                <?php $setting_field = 'loyalty_points'; ?>
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
                                <td colspan="3" style="text-align: center">Tags</td>
                                <td colspan="2" style="text-align: center">Apply if</td>
                            </tr>
                            <tr>
                            <td>name</td>
                            <td>points_value</td>
                            <td>ratio</td>
                            <td>Entity id</td>
                            <td>Price range<br><small><b style="color:red">10-20</b>, or <b style="color:red">>=20</b>, or <b style="color:red"><=5.3</b>, or <b style="color:red">>12.05</b></small></td>
                            </tr>
                        </thead>
                        <?php for($i=0;$i<5;$i++){ ?>
                        <?php
                        $name = '';
                        $points_value = '';
                        $ratio = '';
                        if( isset($setting[$setting_field][$i]['name']) ){
                            $name = $setting[$setting_field][$i]['name'];
                        }
                        if( isset($setting[$setting_field][$i]['points_value']) ){
                            $points_value = $setting[$setting_field][$i]['points_value'];
                        }
                        if( isset($setting[$setting_field][$i]['ratio']) ){
                            $ratio = $setting[$setting_field][$i]['ratio'];
                        }
                        ?>
                        <tr>
                            <td><input type="text" value="<?php echo $name ?>" name="setting[<?php echo $setting_field ?>][<?php echo $i ?>][name]" class="form-control" /></td>
                            <td><input type="text" value="<?php echo $points_value ?>" name="setting[<?php echo $setting_field ?>][<?php echo $i ?>][points_value]" class="form-control" /></td>
                            <td><input type="text" value="<?php echo $ratio ?>" name="setting[<?php echo $setting_field ?>][<?php echo $i ?>][ratio]" class="form-control" /></td>
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