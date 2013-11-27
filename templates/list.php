<div id="page-content">
    <h1 id="page-header">{controller_name_l}</h1>
    <div class="fluid-container">
        <div id="start" style="text-align: left;">
            <ul>
                <li>
                    <a href="<?php echo base_url('admin/{controller_name_l}/add')?>" title="Agregar {controller_name_l}">
                        <i class="cus-add" alt="add"></i>
                        <span>Agregar Nuevo {controller_name_l}</span> 
                    </a>
                </li>
            </ul>
        </div>
        <div class="row-fluid">
            <article class="span12">
                <div class="jarviswidget" id="widget-id-0">
                    <header>
                        <h2>{controller_name_l} del sistema AGENDA</h2>
                        <span class="jarviswidget-loader"> </span>
                    </header>
                    <div>
                        <input type="hidden" id="datatable_url" value="admin/{controller_name_l}/datatable" />
                        <input type="hidden" id="edit_url" value="admin/{controller_name_l}/edit/" />
                        <input type="hidden" id="delete_url" value="admin/{controller_name_l}/delete/" />
                        <div class="inner-spacer">
                            <table class="table table-striped table-bordered responsive" id="table_{controller_name_l}">
                                <thead>
                                    <tr>
                                        {table_headers}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>