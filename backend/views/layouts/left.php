<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [

                    ['label' => 'Control panel', 'options' => ['class' => 'header']],
                    ['label' => 'App', 'icon' => 'folder', 'items' => [
                        ['label' => 'Items', 'icon' => 'file-o', 'url' => ['/items/item/index'], 'active' => $this->context->id == 'items/item'],
                        ['label' => 'Labels', 'icon' => 'file-o', 'url' => ['/items/label/index'], 'active' => $this->context->id == 'items/label'],
                        ['label' => 'Tags', 'icon' => 'file-o', 'url' => ['/items/tag/index'], 'active' => $this->context->id == 'items/tag'],
                        ['label' => 'Categories', 'icon' => 'file-o', 'url' => ['/items/category/index'], 'active' => $this->context->id == 'items/category'],
                        ['label' => 'Parameters', 'icon' => 'file-o', 'url' => ['/items/parameter/index'], 'active' => $this->context->id == 'items/parameter'],
                        ]],
                    ['label' => 'Users', 'icon' => 'user', 'url' => ['/user/index'], 'active' => $this->context->id == 'user'],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                ],
            ]
        ) ?>

    </section>

</aside>
