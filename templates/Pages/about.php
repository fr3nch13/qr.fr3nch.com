<?php
/**
 * @var \App\View\AppView $this
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/view');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>

<h1>What's this all about?</h1>

<section class="px-0 px-md-10 px-lg-20">
    <div class="px-0 px-lg-10 py-lg-10">
        <p>
            I guess you could say I'm a walking contradiction. Much like many of us that don't really find ourselves
            fitting neatly into a bucket that society has simplified into stereotypes.
        </p>
        <p>
            As an example, I love my Tesla. Not because of Elon, or that I'm on some crusade to save the planet,
            but that I no longer have to pay for gas, or be locked into opec. Instead I have solar, and generate my
            own gas.
        </p>
        <p>
            In the other hand, I love riding around in Side by sides. It's such an exhilarating feeling to be able
            to fly through the desert at 60 miles an hour, and feel like you're floating.
        </p>
        <p>
            I've worked in the IT industry for over 20 years. In that time, I've had to wear many hats. From software
            developer (my real passion), to System Administration, to cyber security, to Tier 1 tech support (did you
            turn it off and back on?).
        </p>
        <p>
            However, I also love getting my hands dirty with a saw, wrench, drill, etc. and I'm good at them.
        </p>
        <p>
            Ultimately, the one thing that they all have in common is that I love to create things that people use,
            not just look at. Things that make someone's day just a little bit easier, better, simpler, puts a smile
            in their face.
        </p>
        <p>
            The reason why I'm doing this is to express my individualism in a healthy and productive way, while also
            creating things that I hope will make someone's day just a little bit brighter. Every product is a part
            of me that I'd like to share with the world.
        </p>
        <p>
            I put my heart and soul into every product/project/etc. here, and I hope that can be seen
            in the end product. I hope you enjoy it as much as I do sharing it.
        </p>
        <p class="ps-10">
            - Brian
        </p>
    </div>
</section>

<?= $this->Template->templateComment(false, __FILE__); ?>
