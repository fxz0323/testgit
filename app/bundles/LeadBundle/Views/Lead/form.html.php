<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$view->extend('MauticCoreBundle:Default:content.html.php');
$header = ($lead->getId()) ?
    $view['translator']->trans('mautic.lead.lead.header.edit',
        ['%name%' => $view['translator']->trans($lead->getPrimaryIdentifier())]) :
    $view['translator']->trans('mautic.lead.lead.header.new');
$view['slots']->set('headerTitle', $header);
$view['slots']->set('mauticContent', 'lead');

$groups = array_keys($fields);
sort($groups);

$img = $view['lead_avatar']->getAvatar($lead);
?>

<div class="row">
    <!-- container -->
    <div class="col-md-8 bg-auto height-auto bdr-r">
        <div class="pa-md">

            <?php echo $view['form']->start($form); ?>
            <?php
            foreach ($groups

                     as $key => $group):
                if (isset($fields[$group])):
                    $groupFields = $fields[$group];
                    if (!empty($groupFields)): ?>
                        <div class="tab-pane fade<?php if ($key === 0): echo ' in active'; endif; ?> bdr-rds-0 bdr-w-0"
                             id="<?php echo $group; ?>">
                            <!--                            <div class="pa-md bg-auto bg-light-xs bdr-b">-->
                            <!--                                <h4 class="fw-sb">-->
                            <?php //echo $view['translator']->trans('mautic.lead.field.group.'.$group); ?><!--</h4>-->
                            <!--                            </div>-->

                            <div class="form-group mb-0">
                                <div class="row">
                                    <?php foreach ($groupFields as $alias => $field): ?>
                                        <div class="col-sm-6">
                                            <?php if ($alias == 'company'): ?>
                                                <?php
                                                echo $view['form']->row($form['companies']);
                                                $form[$alias]->setRendered();
                                                ?>
                                            <?php else: ?>
                                                <?php echo isset($form[$alias]) ? $view['form']->row($form[$alias]) : ''; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="col-sm-6">
                                        <?php echo $view['form']->row($form['stage']); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php echo $view['form']->row($form['tags']); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php echo $view['form']->row($form['owner']); ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <?php
                    endif;
                endif;
            endforeach;
            ?>
            <?php echo $view['form']->end($form); ?>
        </div>
    </div>
</div>
