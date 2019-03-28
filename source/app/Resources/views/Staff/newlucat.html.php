<?php
/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */
$this->extend('default.html.php');
?>

<?php 

?>

<?php $language = $this->language;?>
<?php if($this->editmode) : ?>
<style>
table, table td, table th {border:none}
.pimcore_block_button_up, .pimcore_block_button_down {display:none !important;}
#pimcore_editable_rightAreablock .pimcore_area_edit_button,
#pimcore_editable_mainAreablock .pimcore_area_edit_button {z-index: 99;}
#content_sidebar table {margin-bottom:0;}
.info {font-weight:700;margin:0;}
.margins {margin:8px 0 0 0;}
</style>
<?php endif; ?>

<div class="row">
    <div class="col-12 col-lg-8 mb-6 mb-lg-0">
        <?php if ($this->organisation): ?>
            <?php $org = $this->organisation; ?>
            <h1><?php echo $org->getName(); ?></h1>
            <ul>
                <li>Webplats: <a href="<?php echo $org->getUrl(); ?>"><?php echo $org->getUrl(); ?></a></li>
                <li>Telefon: <?php echo $org->getTelephoneNumber(); ?></li>
                <li>Besöksadress: <?php echo $org->getStreet(); ?></li>
                <li>Postadress: <?php echo $org->getPostalAdress(); ?></li>
            </ul>
            <p>Beskrivning:<br>
                <?php echo $org->getDescription(); ?>
            </p>

            <p><a href="<?php echo $this->portalUrl; ?>">Enhetens profil i Lunds universitets forskningsportal</a></p>

            <?php if ($this->persons): ?>
                <h2>Medarbetare</h2>
                <?php $helper = $this->staff($this, $this->persons);
                    echo $helper->staffList(array(
                        'image' => false,
                        'room' => false,
                        'department' => $org->getDepartmentNumber(),
                        'roleinfo' => false,
                        'moreinfo' => true
                    ));
                ?>
            <?php endif ?>

            <?php if ($this->departments): ?>
                <h2>Avdelning(ar)</h2>
                <ul>
                <?php foreach ($this->departments as $department): ?>
                    <?php $href = \Pimcore\Tool::getHostUrl() . $this->document->getFullPath() .'/'; ?>
                    <li><a href="<?php echo $href . $department->getDepartmentNumber(); ?>"><?php echo $department->getName(); ?></a></li>
                <?php endforeach ?>
                </ul>
            <?php endif ?>

            <?php if ($this->gpsC && $this->google): ?>
                <h2>Karta</h2>
                <iframe width="750" height="500" src="https://www.google.com/maps/embed/v1/place?key=<?php echo $this->google->browserapikey; ?>&q=<?php echo $this->gpsC; ?>&zoom=12" width="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
            <?php endif ?>

        <?php else: ?>
            <h1>Ingen Organisation hittades!!!</h1>
        <?php endif ?>
    </div>

            
    <div class="col-12 col-lg-4">
        <?php echo $this->areablock('rightAreablock',array(
            "toolbar"=>0,
            "allowed"=> array("wysiwyg","snippet","youtube","image","uid","infobox","puff")
            ));?>
    </div>
</div>
