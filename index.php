<?php

use app\controllers\LeadsReport;

require_once('assets.php');
require_once('vendor/autoload.php');

session_start();

if (!isset($_SESSION['login']) && $_SESSION['login'] !== true) {
    header('Location: /login.php');
}

$partners = LeadsReport::getPartners();
?>
<div class="container py-4">
    <div class="border border-success rounded p-4">
        <form>
            <div class="py-2">
                <label class="py-1">Выберите парнетров</label>
                <select class="form-select" multiple name="leads_partners" style="min-height: 200px" id="partnerInput">
                    <?php foreach ($partners as $partner): ?>
                        <option value="<?= $partner->mid ?>"><?= $partner->description ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="py-2">
                <label>Выберите нужное количество лидов</label>
                <div>
                    <input class="form-control w-50" type="number" id="leads_quantity">
                </div>
            </div>
            <label>Выберите дату лидов</label>
            <div class="py-2">
                <p>
                    От
                    <input class="form-control w-50" type="date" id="leads_date_s">
                    До
                    <input class="form-control w-50" type="date" id="leads_date_l">
                </p>
            </div>
        </form>
        <div>
            <button class="btn btn-success" id="subButton">
                Сформировать отчет
            </button>
        </div>
        <div class="pt-2">
            <div id="errorBox" style="color: red"></div>
            <div id="successBox"></div>
            <div class="spinner-border d-none pt-2" id="loading" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </div>
</div>
<script src="assets/createReport.js"></script>
