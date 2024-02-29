<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

require_once(Yii::getAlias("@app/helpers/InitLang.php"));
Yii::$app->name = Yii::t("app", "Kanali andmebaas Lite");
AppAsset::register($this);
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$className = "blurple";
$mode = "dark";
$extra = "";
$dsearch = false;
switch (Yii::$app->controller->id) {
    case "ideas":
        $className = "orangellow";
        $mode = "light";
        $extra = "<style>.form-control::placeholder{color: black !important; opacity: 0.5}</style>";
        $dsearch = true;
        break;
    case "gallery":
        $className = "skyblue";
        $dsearch = true;
        break;
    case "video":
        $dsearch = true;
        break;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?= $extra ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'd-flex navbar-expand-lg navbar-'.$mode.' bg-'.$className.' fixed-top']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => Yii::t('app', 'Videod'), 'url' => ['/video/adv-search']],
            ['label' => Yii::t('app', 'Ideed'), 'url' => ['/ideas/adv-search']],
            ['label' => Yii::t('app', 'Galerii'), 'url' => ['/gallery/index']],
            ['label' => Yii::t('app', 'Muuda keelt'), 'url' => ['/lang']],
            ['label' => Yii::t('app', 'Avalehele'), 'url' => '/'],
        ]
    ]);
?>
    <form class="d-flex ms-auto <?= $mode ?>" method="get">
        <?php
            foreach ($_GET as $key => $value) { 
                if ($key != "q") {
                    echo '<input type="hidden" name="'. Html::encode($key) .'" value="'. Html::encode($value) . '">';
                }
            }
        ?>
        <?php if ($dsearch) { ?>
        <input class="form-control flex-shrink-1<?= $mode == "light" ? "text-black border-black" : "" ?> bg-<?= $className ?> me-2" type="search" placeholder="<?= Yii::t('app', 'Märksõna(d)') ?>" aria-label="Keywords" name="q" value="<?= (isset($_GET["q"])?Html::encode($_GET["q"]):"") ?>">
        <button class="btn btn-outline-<?= ($mode == "dark"?"light":"dark") ?> text-<?= ($mode == "dark"?"white":"black") ?>" type="submit"><?= Yii::t('app', 'Otsi') ?></button>
        <?php } ?>
    </form>
<?php
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
