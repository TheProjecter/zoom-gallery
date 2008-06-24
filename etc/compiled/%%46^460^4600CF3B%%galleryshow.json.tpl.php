<?php /* Smarty version 2.6.19, created on 2008-06-24 01:23:02
         compiled from galleryshow.json.tpl */ ?>
<?php echo '
{
'; ?>

    'result': '<?php echo $this->_tpl_vars['zmgAPI']->getParam('result_ok'); ?>
',
    'data': [
        <?php echo '
        {
        '; ?>

        <?php echo $this->_tpl_vars['zmgAPI']->getGallery($this->_tpl_vars['zmgAPI']->getViewToken('last'),'json'); ?>

        <?php echo '
        },
        '; ?>

        <?php $_from = $this->_tpl_vars['zmgAPI']->getMedia($this->_tpl_vars['zmgAPI']->getViewToken('last')); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['mediaiterator'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['mediaiterator']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['medium']):
        $this->_foreach['mediaiterator']['iteration']++;
?>
            <?php echo '{'; ?>

            <?php echo $this->_tpl_vars['medium']->toJSON(); ?>

            <?php echo '}'; ?>
<?php if (! ($this->_foreach['mediaiterator']['iteration'] == $this->_foreach['mediaiterator']['total'])): ?>,<?php endif; ?>
        <?php endforeach; else: ?>
        
        <?php endif; unset($_from); ?>
        <?php echo '
    ]
}
'; ?>
