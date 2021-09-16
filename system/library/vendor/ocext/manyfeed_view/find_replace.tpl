<div class="scrollbox" style="max-height: 350px; overflow-y: auto; width: 100%">
    
    <table class="table table-bordered table-hover">
        <thead>

            <tr>

                <td><?php echo $text_find ?></td>
                <td><?php echo $text_replace ?></td>

            </tr>

        </thead>
        <tr>

            <td>

                <div class="input-group" >
                    <?php if(isset($ym_find_replace['find']) ){ ?>
                        <textarea name="ocext_feed_generator_google_ym_find_replace[find]"  class="form-control select-type-data" ><?php echo $ym_find_replace['find'] ?></textarea>
                    <?php }else{ ?>
                        <textarea name="ocext_feed_generator_google_ym_find_replace[find]" class="form-control select-type-data" ></textarea>
                    <?php } ?>
                </div>

            </td>

            <td>

                <div class="input-group" >
                    <?php if(isset($ym_find_replace['replace']) ){ ?>
                        <textarea name="ocext_feed_generator_google_ym_find_replace[replace]"  class="form-control select-type-data" ><?php echo $ym_find_replace['replace'] ?></textarea>
                    <?php }else{ ?>
                        <textarea name="ocext_feed_generator_google_ym_find_replace[replace]" class="form-control select-type-data" ></textarea>
                    <?php } ?>
                </div>

            </td>

        </tr>
    </table>
    
</div>