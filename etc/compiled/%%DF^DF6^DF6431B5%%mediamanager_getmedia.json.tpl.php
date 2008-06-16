<?php /* Smarty version 2.6.19, created on 2008-06-05 20:32:45
         compiled from mediamanager_getmedia.json.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'mediamanager_getmedia.json.tpl', 5, false),)), $this); ?>
[
<?php $_from = $this->_tpl_vars['zmgAPI']->getMedia($this->_tpl_vars['zmgAPI']->getRequestParamInt('gid'),$this->_tpl_vars['zmgAPI']->getRequestParamInt('offset'),$this->_tpl_vars['zmgAPI']->getRequestParamInt('length'),$this->_tpl_vars['zmgAPI']->getParam('subview')); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['mediaiterator'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['mediaiterator']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['medium']):
        $this->_foreach['mediaiterator']['iteration']++;
?>
    '<img src=<?php echo $this->_tpl_vars['zmgAPI']->jsonHelper($this->_tpl_vars['medium']->getRelPath()); ?>
 id="<?php echo $this->_tpl_vars['medium']->mid; ?>
_lgrid_gen"\/><dl><dt><?php echo $this->_tpl_vars['medium']->name; ?>
<\/dt><dd><b><?php echo $this->_tpl_vars['medium']->descr; ?>
<\/b><\/dd><\/dl>'<?php if (! ($this->_foreach['mediaiterator']['iteration'] == $this->_foreach['mediaiterator']['total'])): ?>,<?php endif; ?>
<?php endforeach; else: ?>
"<b><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>No media to show<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><\/b>"
<?php endif; unset($_from); ?>
]