<?php
/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */
?>

<?php $language = $this->language;?>

<div class="row">
    <div class="col-12 col-lg-8 mb-6 mb-lg-0">
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
                <iframe width="750" height="500" src="https://www.google.com/maps/embed/v1/place?key=<?php echo $this->google->browserapikey; ?>&q=<?php echo $this->gpsC; ?>&zoom=12" width="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
            <?php endif ?>

        <?php else: ?>
            <h1>Ingen Organisation hittades!!!</h1>
        <?php endif ?>
    </div>
</div>
