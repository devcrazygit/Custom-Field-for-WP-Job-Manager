
<div class="wrap">    
    <h2><?php esc_html__('Custom Fields', $plugin_name); ?></h2>
    <div id="nds-wp-list-table-demo">			
        <div id="nds-post-body">		
            <form id="nds-user-list-form" method="get">	
                <?php $list_table->display(); ?>			
            </form>
        </div>
    </div>
</div>