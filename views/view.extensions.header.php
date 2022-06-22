<div class="panel panel-primary">
    <div class="panel-heading"><b><?php echo _("Download PDF") ?></b></div>
    <div class="panel-body">

        <div class="element-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row toolbar">
                        <div class="form-group">

                            <div class="col-md-2">
                                <label class="control-label" for="ls_groups_extensions"><?php echo _("Select Groups to Print"); ?></label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control" id="ls_groups_extensions" multiple="multiple" style="display: none;">
                                <option role="separator" class="divider" disabled>&nbsp;</option>
                                <?php
                                    foreach($ls_ext as $seclectitem)
                                    {
                                        echo sprintf('<option name="module_%1$s" id="module_%1$s" value="%1$s" selected="selected">%2$s</option>', $seclectitem['id'], $seclectitem['title']);
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <div class="box-more-options dropdown">

                                    <form id="form_more_options">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success" title="<?php echo _("Download PDF"); ?>" id="btnPrintPdf">
                                            <span>
                                                <i class="fa fa-file-pdf-o"></i>
                                            </span>
                                        </button>
                                        <button type="button" class="btn btn-default dropdown-toggle" id="dropdownMenuMoreOptions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php echo _("More Options"); ?>">
                                            <span>
                                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                            </span>
                                            <span class="caret"></span>
                                        </button>
                                        
                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuMoreOptions">
                                            <li><b><?php echo _("Header") ?></b></li>
                                            <li>
                                                <div class="input-group">
                                                    <span class="input-group-addon" from="header_title"><?php echo _("Title"); ?></span>
                                                    <input type="text" id="header_title" name="header_title" class="form-control" value="<?php echo $config['header_title'] ?>">
                                                </div>
                                            </li>
                                            <li>
                                                <div class="input-group input-group-radioset">
                                                    <span class="input-group-addon"><?php echo _("Print Header on All Pages"); ?></span>
                                                    <div class="input-group-btn">
                                                        <div class="radioset">
                                                            <input type="radio" name="header_all_pages" id="header_all_pages_y" value="Y" <?php echo $config['header_all_pages'] == "Y" ? "checked" : "" ?>>
                                                            <label for="header_all_pages_y"><i class="fa fa-check-circle" aria-hidden="true"></i></label>

                                                            <input type="radio" name="header_all_pages" id="header_all_pages_n" value="N" <?php echo $config['header_all_pages'] != "Y" ? "checked" : "" ?>>
                                                            <label for="header_all_pages_n"><i class="fa fa-times-circle" aria-hidden="true"></i></label>

                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li role="separator" class="divider"></li>
                                            <li><b><?php echo _("Footer") ?></b></li>
                                            <li>
                                                <div class="input-group">
                                                    <span class="input-group-addon" from="date_format"><?php echo _("Date Format"); ?></span>
                                                    <input type="text" id="date_format" name="date_format" class="form-control" value="<?php echo $config['date_format'] ?>">
                                                </div>
                                            </li>
                                            <li>
                                                <div class="input-group input-group-radioset">
                                                    <span class="input-group-addon"><?php echo _("Date Print Alignment"); ?></span>
                                                    <div class="input-group-btn">
                                                        <div class="radioset">
                                                            <input type="radio" name="align_date" id="align_date_l" value="L" <?php echo $config['align_date'] == "L" ? "checked" : "" ?>>
                                                            <label for="align_date_l"><i class="fa fa-align-left" aria-hidden="true"></i></label>

                                                            <input type="radio" name="align_date" id="align_date_c" value="C" <?php echo $config['align_date'] == "C" ? "checked" : "" ?>>
                                                            <label for="align_date_c"><i class="fa fa-align-center" aria-hidden="true"></i></label>

                                                            <input type="radio" name="align_date" id="align_date_r" value="R" <?php echo $config['align_date'] == "R" ? "checked" : "" ?>>
                                                            <label for="align_date_r"><i class="fa fa-align-right" aria-hidden="true"></i></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="input-group input-group-radioset">
                                                    <span class="input-group-addon"><?php echo _("Pagination Print Alignment"); ?></span>
                                                    <div class="input-group-btn">
                                                        <div class="radioset">
                                                            <input type="radio" name="align_pagination" id="align_pagination_l" value="L" <?php echo $config['align_pagination'] == "L" ? "checked" : "" ?>>
                                                            <label for="align_pagination_l"><i class="fa fa-align-left" aria-hidden="true"></i></label>

                                                            <input type="radio" name="align_pagination" id="align_pagination_c" value="C" <?php echo $config['align_pagination'] == "C" ? "checked" : "" ?>>
                                                            <label for="align_pagination_c"><i class="fa fa-align-center" aria-hidden="true"></i></label>

                                                            <input type="radio" name="align_pagination" id="align_pagination_r" value="R" <?php echo $config['align_pagination'] == "R" ? "checked" : "" ?>>
                                                            <label for="align_pagination_r"><i class="fa fa-align-right" aria-hidden="true"></i></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li role="separator" class="divider"></li>

                                            <li>
                                                <button type="button" class="btn btn-success btn-block" title="<?php echo _("Save Settings"); ?>" id="btn_save_settings">
                                                    <span>
                                                        <i class="fa fa-floppy-o"></i>
                                                    </span>
                                                    <?php echo _("Save Settings") ?>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-danger btn-block" title="<?php echo _("Set Default Settings"); ?>" id="btn_set_default_settings">
                                                    <span>
                                                        <i class="fa fa-undo"></i>
                                                    </span>
                                                    <?php echo _("Set Default Settings") ?>
                                                </button>
                                            </li>
                                        </ul>
                                        
                                    </div>
                                    </form>




                                    

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>