<?php
/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */
?>

<?php $language = $this->document->getProperty('language'); ?>

<?php if($this->event) : ?>
<div class="row">
    <!-- Text content start -->
    <div class="col-12 col-lg-8 mb-6 mb-lg-0">
        <article>
            <?php if ($this->event->getRubrik()): ?>
                <h1><?php echo $this->translate($this->event->getRubrik()) ?></h1>
            <?php endif ?>
            <div class="nmt-3 mb-3">
                <p class="text-uppercase mt-0"><?php echo $this->translate($this->event->getCategory()) ?></p>
            </div>
            <?php if ($this->event->getIngress()): ?>
                <p class="lead"><?php echo $this->event->getIngress(); ?></p>
            <?php endif ?>
            <div>
                <?php if ($this->event->getImage1()): ?>
                    <figure class="figure">
                        <img src="<?php echo $this->event->getImage1(); ?>" class="figure-img img-fluid m-0">
                        <?php if ($this->event->getCaption()) : ?>
                        <figcaption class="figure-caption bg-dark text-white p-2"><?php echo $this->event->getCaption(); ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endif ?>
            </div>
            <?php if ($this->event->getBody()): ?>
                <p><?php echo $this->event->getBody()?></p>
            <?php endif ?>
        </article>
    </div>

    <div class="col-12 col-lg-4">
        <!-- Date -->
        <div class="bg-plaster-25 mb-3 p-3">
            <?php if ($this->event->getStart()): ?>
                    <div class="m-0 p-2 text-center"><i class="fal fa-calendar-alt" style="font-size:72px;"></i></div>
                    <p class="text-center text-uppercase"><strong>
                        <?php
                            $date = $this->calendarEventDate(
                                $language,
                                $this->event->getStart(),
                                $this->event->getEnd(),
                                $this->event->getShowEnd()
                            );
                            $date = str_replace(["kl.", "at"], "<br>", $date);
                            $date = ($this->event->getCancelled() && strpos($date, '<br>')) ? substr($date, 0, strpos($date, '<br>')) : $date;
                            echo $date;
                        ?>
                    </strong></p>
            <?php endif ?>
        </div>
        <!-- Alert -->
         <?php if ($this->event->getAlert() || $this->event->getCancelled()): ?>
             <div class="alert alert-danger" role="alert">
                <?php if ($this->event->getAlert() && $this->event->getCancelled()): ?>
                    <p><?php echo $this->translate('event-canceled'); ?></p>
                    <p><?php echo $this->event->getAlert(); ?></p>
                <?php elseif ($this->event->getAlert()): ?>
                    <p><?php echo $this->event->getAlert(); ?></p>
                <?php else: ?>
                    <p><?php echo $this->translate('event-canceled'); ?></p>
                <?php endif ?>
            </div>
         <?php endif ?>
        <!-- Local -->
        <?php if ($this->lokal && !$this->event->getVenue_later() && !$this->event->getCancelled()): ?>
            <div class="bg-plaster-25 mb-3 p-5">
                <h3 class="mt-0"><?php echo $this->translate('lokal'); ?></h3>
                <p><?php
                    $address = $this->lokal->getAddress();
                    $address = str_replace("\n", "<br>", $address);
                    echo $address;
                ?></p>
                <?php if ($this->coordinate): ?>
                    <iframe src="https://www.google.com/maps/embed/v1/place?key=<?php echo $this->google->browserapikey; ?>&q=<?php echo $this->coordinate; ?>&zoom=12" width="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
                <?php endif ?>
            </div>
        <?php elseif($this->event->getVenue_later()): ?>
            <div class="alert bg-plaster-25" role="alert">
                <p><?php echo $this->translate('lokal_meddelande'); ?></p>
            </div>
        <?php endif ?>
        <!-- Sign-up Button -->
         <?php if (!$this->event->getCancelled() && $this->event->getFormLink() && $this->event->getRegDate() || !$this->event->getCancelled() && $this->event->getFullbokat()): ?>
            <?php $eventLastRegDate = strtotime($this->event->getRegDate()." UTC + 1 day"); ?>
            <?php $eventRegDate = strftime('%d %B %Y', strtotime($this->event->getRegDate()));?>
            <div class="bg-sky-25 mb-3 p-3">
                <h3 class="mt-0"><?php echo $this->translate('register'); ?></h3>
                <?php if ($this->event->getFormLink() && $this->event->getRegDate() && !$this->event->getFullbokat()): ?>
                    <?php if ($eventLastRegDate > time()): ?>
                        <p><?php echo $this->translate('registration-day'); ?>: <?php echo $eventRegDate; ?>.</p>
                        <a href="<?php echo $this->event->getFormLink(); ?>" class="btn btn-secondary"><?php echo $this->translate('register'); ?></a>
                    <?php else: ?>
                        <p><?php echo $this->translate('registration-closed'); ?>.</p>
                    <?php endif ?>
                <?php elseif ($this->event->getFullbokat()): ?>
                    <p><?php echo $this->translate('registration-full'); ?>.</p>
                <?php endif ?>
            </div>
         <?php endif ?>
        <!-- Contact -->
        <?php if ($this->event->getNamn() || $this->event->getEmail() || $this->event->getPhone()): ?>
            <div class="bg-sky-25 mb-3 p-5">
                <h3 class="mt-0"><?php echo $this->translate('kontakt'); ?></h3>
                <?php if ($this->event->getNamn()): ?>
                    <p><strong><?php echo $this->event->getNamn(); ?></strong></p>
                <?php endif ?>
                <?php if ($this->event->getEmail()): ?>
                    <p><a href="mailto:<?php echo $this->event->getEmail($language); ?>"><?php echo $this->event->getEmail(); ?></a></p>
                <?php endif ?>
                <?php if ($this->event->getPhone()): ?>
                    <p><?php echo $this->translate('phone'); ?>: <?php echo $this->event->getPhone(); ?></p>
                <?php endif ?>
            </div>
        <?php endif ?>
    </div>
</div>
<?php endif;?>
