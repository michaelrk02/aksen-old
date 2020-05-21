<div class="w3-padding">
    <h1><?php echo $title; ?></h1>
    <div class="w3-padding">
        <h3>Highlight:</h3>
        <div class="w3-padding">
            <?php echo $info->highlight; ?>
        </div>
    </div>
    <div id="cp" class="w3-padding">
        <h3>List CP:</h3>
        <ul>
            <?php foreach ($contact_persons as $contact_person) { ?>
                <li><?php echo $contact_person->name; ?> (<?php echo $contact_person->role; ?>) &raquo; <a href="https://api.whatsapp.com/send?phone=<?php echo $contact_person->phone_number; ?>">Hubungi (+<?php echo $contact_person->phone_number; ?>)</a></li>
            <?php } ?>
        </ul>
    </div>
</div>
