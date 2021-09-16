
<tr>
                <td>
                    
                    <?php echo $text_setting_template_engine; ?>
                
                </td>
                <td>
                    <?php if( isset($setting['description_constructor']) ){ ?>

                        <textarea name="setting[description_constructor]" placeholder="" id="input-description_constructor" data-toggle="summernote" class="form-control summernote"><?php echo $setting['description_constructor']; ?></textarea>

                    <?php }else{ ?>

                        <textarea name="setting[description_constructor]" placeholder="" id="input-description_constructor" data-toggle="summernote" class="form-control summernote"></textarea>

                    <?php } ?>
                        <script type="text/javascript" src="view/javascript/summernote_manyfeed/summernote.js"></script>
                        <link href="view/javascript/summernote_manyfeed/summernote.css" rel="stylesheet" />
                </td>
            </tr>
            <?php $setting_field = 'claen_descr_html'; ?>
            <tr>
                <td><?php echo $text_setting_claen_descr_html; ?></td>
                <td>
                    <select  class="form-control" name="setting[<?php echo $setting_field; ?>]">
                        <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                            <option selected="" value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            
            <script type="text/javascript">
                $(document).ready(function() {
                        // Override summernotes image manager
                        $('.summernote').each(function() {
                                var element = this;

                                $(element).summernote({
                                        disableDragAndDrop: true,
                                        height: 300,
                                        emptyPara: '',
                                        lang: 'en-GB',
                                        toolbar: [
                                                ['font', ['bold', 'underline', 'clear']],
                                                ['fontname', ['fontname']],
                                                ['color', ['color']],
                                                ['para', ['ul', 'ol', 'paragraph']],
                                                ['table', ['table']],
                                                ['insert', ['link', 'image', 'video']],
                                                ['view', ['fullscreen', 'codeview', 'help']]
                                        ],
                                        buttons: {
                                        image: function() {
                                                        var ui = $.summernote.ui;

                                                        // create button
                                                        var button = ui.button({
                                                                contents: '<i class="note-icon-picture" />',
                                                                tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
                                                                click: function () {
                                                                        $('#modal-image').remove();

                                                                        $.ajax({
                                                                                url: 'index.php?route=common/filemanager&token=' + getURLVar('token'),
                                                                                dataType: 'html',
                                                                                beforeSend: function() {
                                                                                        $('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                                                                                        $('#button-image').prop('disabled', true);
                                                                                },
                                                                                complete: function() {
                                                                                        $('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
                                                                                        $('#button-image').prop('disabled', false);
                                                                                },
                                                                                success: function(html) {
                                                                                        $('body').append('<div id="modal-image" class="modal">' + html + '</div>');

                                                                                        $('#modal-image').modal('show');

                                                                                        $('#modal-image').delegate('a.thumbnail', 'click', function(e) {
                                                                                                e.preventDefault();

                                                                                                $(element).summernote('insertImage', $(this).attr('href'));

                                                                                                $('#modal-image').modal('hide');
                                                                                        });
                                                                                }
                                                                        });						
                                                                }
                                                        });

                                                        return button.render();
                                                }
                                        }
                                });
                        });

                });
            </script>