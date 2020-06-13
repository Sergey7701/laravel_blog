@include ('layouts.header', ['title' => 'Статистика сайта'])
<?php
\App::setLocale('ru');

?>
<form method="POST" action="/admin/report" class="col-6">
    {{ csrf_field() }}
    <h3>Статистика сайта:</h3>
    <?php
    foreach ($skeleton as $inputName => $optionsTexts) {
        $i = 0;
        foreach ($optionsTexts as $text => $unused) {
            if (!$i) {
                $i++;

                ?>
                <label class="col-12">
                    {{ __('statisticReport.' . $text) }}
                    <input type="checkbox" name="{{ $inputName }}">
                </label>
                <?php
            } else {

                ?>
                <p class="col-12 mr-1">
                    {{ __('statisticReport.' . $text) }}
                </p><?php
            }
        }

        ?>
        <hr>
        <?php
    }

    ?>

    <input name="send" type="submit">
</form>
<div class="col-6" id="statistic-result">
    <statistic-result-component></statistic-result-component>
</div>
@include ('layouts.footer')