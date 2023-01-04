import editorstyles from '../../editorstyles.json'

pimcore.document.editables.wysiwyg.defaultEditorConfig = pimcore.document.editables.wysiwyg.defaultEditorConfig || {}

// additional ck editor styles for documents
pimcore.document.editables.wysiwyg.defaultEditorConfig.stylesSet = [
  ...(pimcore.document.editables.wysiwyg.defaultEditorConfig.stylesSet || []),
  ...editorstyles,
]

if (window?.app?.settings?.appBundleAssetRoot)
  pimcore.document.editables.wysiwyg.defaultEditorConfig.contentsCss = [
    CKEDITOR.getUrl('contents.css'),
    window.app.settings.appBundleAssetRoot + '/styles/editmode.css',
  ]