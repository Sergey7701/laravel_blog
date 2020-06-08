@include ('layouts.header', ['title' => 'Статистика сайта'])
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
                    {{ $text }}
                    <input type="checkbox" name="{{ $inputName }}">
                </label>
                <?php
            } else {

                ?>
                <p class="col-12 mr-1">
                    {{ $text }}
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