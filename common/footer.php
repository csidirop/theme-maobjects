</article><!-- end content -->
</div><!-- end wrap -->

<?php
$footerSections = maobjects_footer_sections();
$footerBrandingLogos = maobjects_footer_branding_logos();
$legalLinks = maobjects_footer_legal_links();
$footerText = (string) get_theme_option('footer_text');
$socialLinks = maobjects_footer_social_links();
$isHomePage = maobjects_is_home_page(!empty($is_home_page)); // $is_home_page is set by the Simple Pages plugin when the page is set as the homepage in the navigation.
$footerUsesGradient = get_theme_option('footer_use_gradient') === '1';

$isFloatingHomepage = get_theme_option('floating_homepage') === '1' && $isHomePage;
$footerClasses = 'uma-footer';
if ($isFloatingHomepage) { $footerClasses .= ' uma-footer-compact-home'; }
if ($footerUsesGradient) { $footerClasses .= ' uma-footer-gradient'; }
?>

<footer role="contentinfo" class="<?php echo html_escape($footerClasses); ?>">
  <div class="uma-footer-shell">
    <div class="row expanded align-center">
      <div class="column small-16 footer-inner">
      <!-- Regular Footer -->
      <?php if (!$isFloatingHomepage): ?>
      <div class="main-footer-navigation row">
        <!-- Footer Logos: -->
        <?php if ($footerBrandingLogos): ?>
        <div class="column small-16 large-4 footer-branding">
          <?php foreach ($footerBrandingLogos as $logo): ?>
          <div class="footer-branding-item footer-branding-<?php echo html_escape($logo['slot']); ?>">
            <?php if ($logo['label'] !== ''): ?>
            <span class="footer-branding-label"><?php echo html_escape($logo['label']); ?></span>
            <?php endif; ?>
            <img class="footer-branding-image" src="<?php echo html_escape($logo['src']); ?>" alt="" />
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Footer Sections: -->
        <?php foreach ($footerSections as $section): ?>
        <div class="column small-16 medium-8 large-4 footer-nav">
          <?php if ($section['title'] !== ''): ?>
          <h4><span><?php echo html_escape($section['title']); ?></span></h4>
          <?php endif; ?>

          <?php if ($section['links']): ?>
          <ul id="<?php echo html_escape($section['id']); ?>">
            <?php foreach ($section['links'] as $link): ?>
            <li>
              <a href="<?php echo html_escape($link['href']); ?>"
                <?php if (!empty($link['target'])): ?>target="<?php echo html_escape($link['target']); ?>" rel="<?php echo html_escape($link['rel']); ?>"<?php endif; ?>>
                <?php echo html_escape($link['label']); ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Footer Text: -->
      <?php if (trim(strip_formatting($footerText)) !== ''): ?>
      <div class="main-footer-text row">
        <div class="column small-16">
          <?php echo $footerText; ?>
        </div>
      </div>
      <?php endif; ?>
      <?php endif; ?>

      <!-- Legal Information: -->
      <?php if ($legalLinks || $socialLinks || ($isFloatingHomepage && $footerBrandingLogos)): ?>
      <div class="main-footer-legal-info row align-middle align-justify medium-text-center">
        <?php if ($legalLinks): ?>
        <div class="column small-16 <?php echo $isFloatingHomepage ? 'large-8' : 'large-12'; ?> footer-nav footer-nav-flat">
          <ul>
            <?php foreach ($legalLinks as $link): ?>
            <li>
              <a href="<?php echo html_escape($link['href']); ?>"
                <?php if (!empty($link['target'])): ?>target="<?php echo html_escape($link['target']); ?>" rel="<?php echo html_escape($link['rel']); ?>"<?php endif; ?>>
                <?php echo html_escape($link['label']); ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Floating Footer branding -->
        <?php if ($isFloatingHomepage && $footerBrandingLogos): ?>
        <div class="column small-16 <?php echo $legalLinks ? 'large-8' : 'large-16'; ?> footer-branding footer-branding-compact">
          <?php foreach ($footerBrandingLogos as $logo): ?>
          <div class="footer-branding-item footer-branding-<?php echo html_escape($logo['slot']); ?>">
            <?php if ($logo['label'] !== ''): ?>
            <span class="footer-branding-label"><?php echo html_escape($logo['label']); ?></span>
            <?php endif; ?>
            <img class="footer-branding-image" src="<?php echo html_escape($logo['src']); ?>" alt="" />
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Social Media Links: -->
        <?php if (!$isFloatingHomepage && $socialLinks): ?>
        <div class="column small-16 large-4">
          <div class="social-icons">
            <?php foreach ($socialLinks as $socialLink): ?>
            <a href="<?php echo html_escape($socialLink['href']); ?>"
              target="_blank"
              rel="<?php echo html_escape($socialLink['rel']); ?>"
              aria-label="<?php echo html_escape($socialLink['label']); ?>">
              <?php echo maobjects_footer_social_icon($socialLink['platform']); ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      </div>
    </div>
  </div>
  <?php fire_plugin_hook('public_footer', array('view' => $this)); ?>
</footer>

<div id="scroll-to-top"></div>
<div class="cookie-notice">
  <div class="cookie-notice-msg">Um unsere Webseite für Sie optimal zu gestalten und fortlaufend verbessern zu können, verwenden wir Cookies und speichern anonyme Nutzungsdaten. Weitere Informationen erhalten Sie in unserer <a href="https://www.uni-mannheim.de/datenschutzerklaerung/" target="_blank">Datenschutzerklärung</a>.</div>
  <div class="cookie-accept cookie-button button">Erlauben</div>
  <div class="cookie-reject cookie-button button">Ablehnen</div>
</div>
<div class="cookie-controls hide">
  <div class="cookie-status cookie-status-accepted">
    <p>Tracking ist derzeit zugelassen.</p>
    <div class="cookie-reject cookie-button button">Tracking nicht erlauben</div>
  </div>
  <div class="cookie-status cookie-status-rejected">
    <p>Tracking ist derzeit nicht zugelassen.</p>
    <div class="cookie-accept cookie-button button">Tracking erlauben</div>
  </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        Omeka.skipNav();
        CenterRow.megaMenu();
    });
</script>

</body>

</html>
