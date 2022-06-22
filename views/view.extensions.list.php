<div class="fpbx-container">
    <div class="display full-border">
        <div class="row holder">
            <div class="col-sm-12">
            <?php 
                foreach ($ls_ext as $k => $v)
                {
                    $html_body  = '<div class="row" id="%s"><h3>%s</h3><ul class="list-group">%s</ul></div>';
                    $html_li 	= '<li class="list-group-item col-sm-6"><span class="list-group-item col-sm-12">%s</span></li>';
                    $li_list 	= "";

                    if (count($v['items']) == 0)
                    {
                        $li_list = sprintf($html_li, '<b>'._("Empty").'</b>');
                    }
                    else
                    {
                        foreach ($v['items'] as $item)
                        {
                            $li_list .= sprintf($html_li, sprintf('<b>%s</b> - %s</li>', $item[1], $item[0]));											
                        }
                    }
                    echo sprintf($html_body, $v['id'], $v['title'], $li_list);
                }
                ?>
            </div>
        </div>
    </div>
</div>