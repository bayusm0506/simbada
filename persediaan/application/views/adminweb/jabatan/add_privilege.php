<div class="row">
            <div class="span12">

                <div class="panel">
                    <div class="panel-header">
                        <i class="icon-sign-blank"></i>
                        <?php echo ! empty($header) ?  $header: 'Privilage'; ?>
                        <span style="color:#428bca"><?php echo $jabatan;?></span>
                    </div>
                    <div class="panel-content">

                    <div class="table-responsive">
                        <form id="form_check" method="post" action="<?php echo $form_action; ?>">
                            <input type="hidden" name="jabatan_id" value="<?php echo $id;?>">
                            <table id="data-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Modul
                                            <br/>&nbsp;</th>
                                        <th>View
                                            <br>
                                            <input value="1" type="checkbox" onClick="checkAll('View', this.checked)">
                                        </th>
                                        <th>New
                                            <br>
                                            <input value="1" type="checkbox" onClick="checkAll('Add', this.checked)">
                                        </th>
                                        <th>Edit
                                            <br>
                                            <input value="1" type="checkbox" onClick="checkAll('Edit', this.checked)">
                                        </th>
                                        <th>Delete
                                            <br>
                                            <input value="1" type="checkbox" onClick="checkAll('Remove', this.checked)">
                                        </th>
                                        <th>Cetak
                                            <br>
                                            <input value="1" type="checkbox" onClick="checkAll('Cetak', this.checked)">
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $tr;?>
                                </tbody>
                            </table>

                            <button type="submit" class="btn medium ui-state-default radius-all-4">Simpan</button>
                            <a href="<?php echo base_url();?>jabatan" class="btn medium ui-state-default radius-all-4"> Batal &nbsp;</a>

                        </form>
                    </div>

                    </div>
                </div>

            </div>
        </div>

<script type="text/javascript">
    function checkAll(type, condition) {
        formz = document.forms['form_check'];
        len = formz.elements.length;
        for (i = 0; i < len; i++) {
            if (formz.elements[i] && formz.elements[i].type == 'checkbox') {
                if (formz.elements[i].id == type) formz.elements[i].checked = condition;
            }
        }
    }
</script>