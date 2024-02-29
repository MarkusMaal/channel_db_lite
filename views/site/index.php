<?php
use yii\helpers\Url;
use yii\web\Cookie;

/** @var yii\web\View $this */
require_once(Yii::getAlias("@app/helpers/InitLang.php"));
$this->title = Yii::t('site', 'Kanali andmebaas');
?>
<div class="site-index">
    <div class="jumbotron bg-light p-5 mt-5 mb-5 d-flex">
    <img class="me-5" src="<?= Url::to("@web/channel_db_lite.svg", true) ?>">
        <div>asf
        <h1 class="display-5"><?= Yii::t('app', 'Kanali andmebaas Lite') ?></h1>
                <?php isset($_GET["q"])?Yii::$app->session->setFlash('blurple', Yii::t('site', 'Otsimiseks palun sisenege videote, ideede või galerii lehele')):"" ?>
                <p class="lead"><?= Yii::t('site', 'Uue koodiga versioon kanali andmebaasist, mis on kirjutatud {0}. Kanali andmebaas Lite ei sisalda üksikuid funktsioone, mis olid varasemas versioonis, sealhulgas...', ["<a class='link-blurple' href='https://yiiframework.com' target='_blank'>".Yii::t("site", "Yii raamistikus")."</a>"]) ?></p>
                <ul>
                    <li><?= Yii::t('site', 'Raporti salvestamine CSV vormingusse'); ?></li>
                    <li><?= Yii::t('site', 'Pärandrežiim'); ?></li>
                </ul>
        <a class="btn btn-lg btn-blurple me-2" href="<?= Url::to(["/video/adv-search"]) ?>"><?= Yii::t("app", "Videod") ?></a>
        <a class="btn btn-lg btn-orangellow me-2" href="<?= Url::to(["/ideas/adv-search"]) ?>"><?= Yii::t("app", "Ideed") ?></a>
        <a class="btn btn-lg btn-skyblue me-2" href="<?= Url::to(["/gallery/index"]) ?>"><?= Yii::t("app", "Galerii") ?></a>
        <a class="btn btn-lg btn-primary me-2" href="<?= Url::to("/channel_db?ord=id&dir=DESC", true) ?>"><?= Yii::t("app", "Täisversioon") ?></a>
        <a class="btn btn-lg btn-secondary" href="<?= Url::to("https://markustegelane.eu/channel_db_old") ?>"><?= Yii::t("app", "Vana kujundus") ?></a>
        </div>
    </div>
    <div>
        <h3 style="font-weight: normal;"><?= Yii::t('site', 'Kuid milleks luua veel üks uus version kanali andmebaasist?'); ?></h3>
        <p><?= Yii::t('site', 'Põhjuseid on mitmeid, kuid siin on mõned olulisemad:') ?></p>
        <ul>
            <li><?= Yii::t('site', 'Hoolimata sellest, et kanali andmebaas näeb väga viisakas välja, kasutab see endiselt 2020. aastal kirjutatud koodi. See on oluline, sest:'); ?>
                <ul>
                    <li> <?= Yii::t('site', 'Mul oli siis vähem kogemusi PHP rakenduste kirjutamise osas (olin siis alles algaja)'); ?> </li>
                    <li> <?= Yii::t('site', 'Suur osa koodist asus ühes failis ning seetõttu oli selle lugemine raskendatud, mis muutis uute versioonide loomise keerulisemaks'); ?> </li>
                </ul>
            </li>
            <li><?= Yii::t('site', 'Tahtsin õppida Yii2 raamistikku kasutama ning leidsin, et kanali andmebaasi värskendamine on üks hea vaba aja väljakutse'); ?></li>
            <li><?= Yii::t('site', 'Vana kanali andmebaas on täis haavatavusi ja veidraid bugisid, mis on vaja likvideerida'); ?></li>
            <li><?= Yii::t('site', 'Yii2 sisaldab paremaid silumistööriistu vigade leidmiseks'); ?></li>
        </ul>
    </div>
</div>
