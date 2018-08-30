<h4 class="heading-1 clearfix">
    <div class="heading-content">
        <?php echo $header; ?>
        <small>
            Choose between tons of combinations &amp; possibilites that our themes offer to create all kinds of form elements &amp; layouts.
        </small>
    </div>
    <div class="clear"></div>
    <div class="divider"></div>
</h4>

<div class="example-code">

        <form id="demo-form-2" action="<?php echo $form_action; ?>" class="col-md-10 center-margin" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Jabatan
                        <span class="required">*</span>
                    </label>
                </div>
                <div class="form-input col-md-4">
                    <input type="text" id="jabatan" placeholder="Tulis jabatan disini" name="jabatan" data-trigger="change" data-required="true" class="parsley-validated" value="<?php echo $row['jabatan']; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Keterangan
                        <span class="required">*</span>
                    </label>
                </div>
                <div class="form-input col-md-6">
                    <textarea class="parsley-validated" cols="80" rows="10" name="keterangan" data-trigger="keyup"><?php echo $row['keterangan']; ?></textarea>
                </div>
            </div>
            <div class="divider"></div>
            <div class="form-row">
                <div class="form-input col-md-10 col-md-offset-2">
                    <button type="submit" class="btn medium ui-state-default radius-all-4">Update</button>
                </div>
            </div>

        </form>

    </div>

<script type="text/javascript">
    $(document).ready(function() {
          
    $('form').on('submit', function (e) {
        var $this;
        $this = $(this);
        if ($this.parsley('validate')) {
            return true;
        } else {
            alert('tidak oke');
        }

        e.preventDefault();
        return false;
    });
  });
  </script>