<?php
/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */
$this->extend('default.html.php');
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

<?php if ($this->document->getProperty('bootstrap')) : ?>
    <?= $this->template("Bootstrap/Staff/newlucat.html.php"); ?>
<?php else : ?>

<div id="text_wrapper" class="grid-15<?php echo ((!$this->getProperty('navStartNode')) || ($this->getProperty('push-8'))) ? " push-8 alpha" : ""; ?>">
    <article id="text_content_main">
        <?php if ($this->organisation): ?>
            <?php $org = $this->organisation; ?>
            <h1><?php echo $org->getName(); ?></h1>
            <ul>
                <?php if ($org->getUrl()): ?>
                    <li><?php echo $this->translate('website') ?>: <a href="<?php echo $org->getUrl(); ?>"><?php echo $org->getUrl(); ?></a></li>
                <?php endif ?>

                <?php if ($org->getTelephoneNumber()): ?>
                    <li><?php echo $this->translate('phone') ?>: <?php echo $org->getTelephoneNumber(); ?></li>
                <?php endif ?>

                <?php if ($org->getStreet()): ?>
                <li><?php echo $this->translate('visiting_address') ?>: <?php echo $org->getStreet(); ?></li>    
                <?php endif ?>

                <?php if ($org->getPostalAdress()): ?>
                    <li><?php echo $this->translate('postal_address') ?>: <?php echo $org->getPostalAdress(); ?></li>
                <?php endif ?>
            </ul>

            <?php if ($org->getDescription()): ?>
            <p><?php echo $this->translate('description') ?>:</p>
            <p><?php echo $org->getDescription(); ?></p>
            <?php endif ?>

            <?php if ($this->portalUrl): ?>
                <p><a href="<?php echo $this->portalUrl; ?>"><?php echo $this->translate('my_profile_lucris'); ?></a></p>
            <?php endif ?>

            <?php if ($this->persons): ?>
                <h2><?php echo $this->translate('coworker'); ?></h2>
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
                <h2><?php echo $this->translate('section'); ?></h2>
                <ul>
                <?php foreach ($this->departments as $department): ?>
                    <?php $href = \Pimcore\Tool::getHostUrl() . $this->document->getFullPath() .'/'; ?>
                    <li><a href="<?php echo $href . $department->getDepartmentNumber(); ?>"><?php echo $department->getName(); ?></a></li>
                <?php endforeach ?>
                </ul>
            <?php endif ?>

            <?php if ($this->gpsC && $this->google): ?>
                <h2><?php echo $this->translate('map'); ?></h2>
                <iframe width="430" height="500" src="https://www.google.com/maps/embed/v1/place?key=<?php echo $this->google->browserapikey; ?>&q=<?php echo $this->gpsC; ?>&zoom=12" width="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
            <?php endif ?>

        <?php else: ?>
            <h1>Ingen Organisation hittades!!!</h1>
        <?php endif ?>
    </article>
</div>

<?php endif; ?>