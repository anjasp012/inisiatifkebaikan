@props(['model'])

<div wire:ignore x-data="{
    value: @entangle($model),
    init() {
        CKEDITOR.ClassicEditor
            .create($refs.editor, {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                    'imageUpload', 'insertTable', 'undo', 'redo'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'H1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'H2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'H3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'H4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'H5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'H6', class: 'ck-heading_heading6' }
                    ]
                },
                image: {
                    toolbar: [
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side',
                        '|',
                        'toggleImageCaption',
                        'imageTextAlternative',
                        '|',
                        'resizeImage'
                    ],
                    resizeOptions: [{
                            name: 'resizeImage:original',
                            label: 'Original',
                            value: null
                        },
                        {
                            name: 'resizeImage:100',
                            label: '100%',
                            value: '100'
                        },
                        {
                            name: 'resizeImage:75',
                            label: '75%',
                            value: '75'
                        },
                        {
                            name: 'resizeImage:50',
                            label: '50%',
                            value: '50'
                        },
                        {
                            name: 'resizeImage:25',
                            label: '25%',
                            value: '25'
                        }
                    ],
                },
                removePlugins: [
                    'AIAssistant',
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    'MathType',
                    'SlashCommand',
                    'Template',
                    'DocumentOutline',
                    'FormatPainter',
                    'TableOfContents',
                    'PasteFromOfficeEnhanced',
                    'CaseChange'
                ],
            })
            .then(editor => {
                editor.setData(this.value);
                editor.model.document.on('change:data', () => {
                    this.value = editor.getData();
                });

                // Watch for external changes (like when switching between items if using same component)
                $watch('value', (newValue) => {
                    if (newValue !== editor.getData()) {
                        editor.setData(newValue || '');
                    }
                });
            })
            .catch(error => {
                console.error(error);
            });
    }
}" {{ $attributes }}>
    <div x-ref="editor"></div>
</div>
