import editorstyles from '../../editorstyles.json'

pimcore.object.tags.wysiwyg.defaultEditorConfig = pimcore.object.tags.wysiwyg.defaultEditorConfig || {}

pimcore.object.tags.wysiwyg.defaultEditorConfig.stylesSet = [
  ...(pimcore.object.tags.wysiwyg.defaultEditorConfig.stylesSet || []),
  ...editorstyles,
]