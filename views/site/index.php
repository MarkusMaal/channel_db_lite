<?php
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'Kanali andmebaas';
?>
<div class="site-index">

    <div class="jumbotron bg-transparent mt-5 mb-5">
        <h1 class="display-5">Kanali andmebaas Lite</h1>
        <?php isset($_GET["q"])?Yii::$app->session->setFlash('info', 'Otsimiseks palun sisenege videote, ideede või galerii lehele'):"" ?>
        <p class="lead">Uue koodiga versioon kanali andmebaasist, mis on kirjutatud <a target="_blank" href="<?= Url::to("https://www.yiiframework.com") ?>">Yii2 raamistikus</a>. Hetkel puuduvad teatud funktsioonid, sealhulgas...</p>
        <ul>
            <li>Mitme keele tugi</li>
            <li>Raporti koostamine ja salvestamine erinevates formaatidesse</li>
            <li>Kommentaarid (videod alamleht)</li>
            <li>Tulemuste sorteerimine</li>
            <li>Ühel leheküljel kuvatavate tulemuste arvu muutmine</li>
            <li>Piltide peitmine tulemustelehelt</li>
            <li>Pärandrežiim (pole plaanis seda lisada, sest selle väärtus varasemas versioonis oli ka kaheldav)</li>
        </ul>
        <h3 style="font-weight: normal;">Kuid milleks luua veel üks uus version kanali andmebaasist?</h3>
        <p>Põhjuseid on mitmeid, kuid siin on mõned olulisemad:</p>
        <ul>
            <li>Hoolimata sellest, et kanali andmebaas näeb väga viisakas välja, kasutab see endiselt 2020. aastal kirjutatud koodi. See on oluline, sest:
                <ul>
                    <li> Mul oli siis vähem kogemusi PHP rakenduste kirjutamise osas (olin siis alles algaja) </li>
                    <li> Suur osa koodist asus ühes failis ning seetõttu oli selle lugemine raskendatud, mis muutis uute versioonide loomise keerulisemaks </li>
                </ul>
            </li>
            <li>Tahtsin õppida Yii2 raamistikku kasutama ning leidsin, et kanali andmebaasi värskendamine on üks hea vaba aja väljakutse</li>
            <li>Vana kanali andmebaas on täis haavatavusi ja veidraid bugisid, mis on vaja likvideerida</li>
            <li>Yii2 sisaldab paremaid silumistööriistu vigade leidmiseks</li>
        </ul>
        <a class="btn btn-lg btn-primary me-2" href="<?= Url::to(["/video/adv-search"]) ?>">Videod</a>
        <a class="btn btn-lg btn-warning me-2" href="<?= Url::to(["/ideas/adv-search"]) ?>">Ideed</a>
        <a class="btn btn-lg btn-success me-2" href="<?= Url::to(["/gallery/index"]) ?>">Galerii</a>
        <a class="btn btn-lg btn-secondary me-2" href="<?= Url::to("/channel_db?ord=id&dir=DESC", true) ?>">Täisversioon</a>
        <a class="btn btn-lg btn-secondary" href="<?= Url::to("https://markustegelane.eu/channel_db_old") ?>">Vana kujundus</a>
    </div>
</div>
