
<?php if(isset($columns) && $columns && isset($operators) && $operators){ ?>

    <div class="scrollbox" style="max-height: 350px; overflow-y: auto; width: 100%">
    
    <table class="table table-bordered table-hover">
        <thead>

            <tr>

                <td><?php echo $text_p_column ?></td>
                <td><?php echo $text_p_operator ?></td>
                <td><?php echo $text_p_value ?></td>
                <td><?php echo $text_p_logic ?></td>

            </tr>

        </thead>
        <?php for($i=0;$i<5;$i++){ ?>


                    <tr>

                        <td>

                            <div class="input-group" >
                                <select name="ocext_feed_generator_google_ym_filter_columns[product][<?php echo $i ?>][product_field]"  class="form-control select-type-data">
                                    <option value="0" ><?php echo $text_p_set ?></option>
                                        <?php foreach($columns as $product_field => $product_value){ ?>
                                            <?php if(isset($ym_columns['product'][$i]['product_field']) && $ym_columns['product'][$i]['product_field']==$product_field ){ ?>
                                    <option value="<?php echo $product_field ?>" selected="" ><?php echo $product_value; ?></option>
                                            <?php }else{ ?>
                                    <option value="<?php echo $product_field ?>" ><?php echo $product_value; ?></option> 
                                            <?php } ?>
                                        <?php } ?>
                                </select>
                            </div>

                        </td>

                        <td>

                            <div class="input-group" >
                                <select name="ocext_feed_generator_google_ym_filter_columns[product][<?php echo $i ?>][operator]"  class="form-control select-type-data">
                                    <option value="0" ><?php echo $text_p_set ?></option>
                                        <?php foreach($operators as $product_field => $product_value){ ?>
                                            <?php if(isset($ym_columns['product'][$i]['operator']) && $ym_columns['product'][$i]['operator']==$product_field ){ ?>
                                    <option value="<?php echo $product_field ?>" selected="" ><?php echo $product_value; ?></option>
                                            <?php }else{ ?>
                                    <option value="<?php echo $product_field ?>" ><?php echo $product_value; ?></option> 
                                            <?php } ?>
                                        <?php } ?>
                                </select>
                            </div>

                        </td>

                        <td>

                            <div class="input-group" >

                                <?php if(isset($ym_columns['product'][$i]['value']) ){ ?>
                                    <input name="ocext_feed_generator_google_ym_filter_columns[product][<?php echo $i ?>][value]"  value="<?php echo $ym_columns['product'][$i]['value'] ?>" class="form-control select-type-data" type="text" />
                                <?php }else{ ?>
                                    <input name="ocext_feed_generator_google_ym_filter_columns[product][<?php echo $i ?>][value]" value=""  class="form-control select-type-data" type="text" />
                                <?php } ?>

                            </div>

                        </td>
                        
                        <td>

                            <div class="input-group" >
                                <select name="ocext_feed_generator_google_ym_filter_columns[product][<?php echo $i ?>][logic]"  class="form-control select-type-data">
                                        <?php foreach($logics as $product_field => $product_value){ ?>
                                            <?php if(isset($ym_columns['product'][$i]['logic']) && $ym_columns['product'][$i]['logic']==$product_field ){ ?>
                                    <option value="<?php echo $product_field ?>" selected="" ><?php echo $product_value; ?></option>
                                            <?php }else{ ?>
                                    <option value="<?php echo $product_field ?>" ><?php echo $product_value; ?></option> 
                                            <?php } ?>
                                        <?php } ?>
                                </select>
                            </div>

                        </td>

                    </tr>


        <?php } ?>
    </table>
    
</div>


<?php } ?>