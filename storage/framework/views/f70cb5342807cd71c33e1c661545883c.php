

<?php if (! $__env->hasRenderedOnce('2b61344b-cd78-4b0b-8300-6ea87aaf0065')): $__env->markAsRenderedOnce('2b61344b-cd78-4b0b-8300-6ea87aaf0065'); ?>
    <?php $__env->startPush('styles'); ?>
        <style>
            .mp-modal {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .6);
                z-index: 1000;
                align-items: center;
                justify-content: center;
                padding: 16px;
            }

            .mp-modal.open {
                display: flex;
            }

            .mp-box {
                background: #fff;
                border-radius: 16px;
                width: 100%;
                max-width: 860px;
                max-height: 90vh;
                display: flex;
                flex-direction: column;
                box-shadow: 0 20px 60px rgba(0, 0, 0, .3);
                overflow: hidden;
            }

            .mp-tabs {
                display: flex;
                border-bottom: 1px solid #e5e7eb;
            }

            .mp-tab {
                flex: 1;
                padding: 13px;
                text-align: center;
                font-size: 13px;
                font-weight: 600;
                color: #6b7280;
                cursor: pointer;
                border-bottom: 2px solid transparent;
                transition: all .15s;
                background: none;
                border-top: none;
                border-left: none;
                border-right: none;
                font-family: inherit;
            }

            .mp-tab.active {
                color: var(--teal);
                border-bottom-color: var(--teal);
                background: var(--teal-bg);
            }

            .mp-body {
                flex: 1;
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }

            /* Browse tab */
            .mp-search-bar {
                padding: 12px 14px;
                border-bottom: 1px solid #f3f4f6;
                display: flex;
                gap: 8px;
            }

            .mp-search-bar input {
                flex: 1;
                border: 1px solid #d1d5db;
                border-radius: 8px;
                padding: 8px 12px;
                font-size: 13px;
                outline: none;
            }

            .mp-search-bar input:focus {
                border-color: var(--teal);
                box-shadow: 0 0 0 3px var(--teal-bg);
            }

            .mp-grid {
                flex: 1;
                overflow-y: auto;
                padding: 14px;
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 10px;
                align-content: start;
            }

            .mp-item {
                border-radius: 10px;
                overflow: hidden;
                border: 2px solid transparent;
                cursor: pointer;
                transition: all .15s;
                background: #f8fafb;
            }

            .mp-item:hover {
                border-color: var(--teal-light);
                transform: scale(1.02);
            }

            .mp-item.selected {
                border-color: var(--teal);
                box-shadow: 0 0 0 2px var(--teal-bg);
            }

            .mp-item-img {
                aspect-ratio: 1;
                overflow: hidden;
            }

            .mp-item-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .mp-item-name {
                font-size: 9px;
                color: #6b7280;
                padding: 3px 5px 4px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            /* Upload tab */
            .mp-upload-area {
                padding: 14px;
                overflow-y: auto;
                flex: 1;
            }

            .mp-dropzone {
                border: 2px dashed #d1d5db;
                border-radius: 12px;
                padding: 32px;
                text-align: center;
                cursor: pointer;
                transition: all .15s;
                margin-bottom: 14px;
            }

            .mp-dropzone:hover,
            .mp-dropzone.drag-over {
                border-color: var(--teal);
                background: var(--teal-bg);
            }

            .mp-queue {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-bottom: 14px;
            }

            .mp-queue-item {
                position: relative;
                width: 72px;
                height: 72px;
                border-radius: 8px;
                overflow: hidden;
                background: #f3f4f6;
                flex-shrink: 0;
            }

            .mp-queue-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .mp-queue-item .mp-progress {
                position: absolute;
                inset: 0;
                background: rgba(0, 0, 0, .5);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 11px;
                font-weight: 700;
            }

            .mp-queue-item .mp-done {
                background: rgba(16, 185, 129, .8);
            }

            .mp-queue-item .mp-error {
                background: rgba(220, 38, 38, .8);
                font-size: 9px;
            }

            /* Footer */
            .mp-footer {
                padding: 12px 14px;
                border-top: 1px solid #f3f4f6;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                background: #f9fafb;
            }

            .mp-empty {
                grid-column: 1/-1;
                text-align: center;
                padding: 40px;
                color: #9ca3af;
                font-size: 13px;
            }

            .mp-loading {
                grid-column: 1/-1;
                text-align: center;
                padding: 40px;
                color: #9ca3af;
            }
        </style>
    <?php $__env->stopPush(); ?>

    <div id="media-picker-modal" class="mp-modal" onclick="if(event.target===this)closeMediaPicker()">
        <div class="mp-box">

            
            <div
                style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid #e5e7eb;flex-shrink:0">
                <h3 style="font-weight:700;font-size:15px;color:#1f2937">🖼 Media Library</h3>
                <button onclick="closeMediaPicker()"
                    style="background:none;border:none;font-size:22px;color:#9ca3af;cursor:pointer;line-height:1;padding:0 4px">&times;</button>
            </div>

            
            <div class="mp-tabs" style="flex-shrink:0">
                <button class="mp-tab active" id="mp-tab-browse" onclick="mpSwitchTab('browse')">
                    <i class="fas fa-images mr-1.5"></i> Browse Library
                </button>
                <button class="mp-tab" id="mp-tab-upload" onclick="mpSwitchTab('upload')">
                    <i class="fas fa-cloud-upload-alt mr-1.5"></i> Upload New
                </button>
            </div>

            
            <div id="mp-browse" class="mp-body">
                <div class="mp-search-bar">
                    <input type="text" id="mp-search" placeholder="Search by filename…" oninput="mpSearch(this.value)">
                    <select id="mp-folder-filter" onchange="mpSearch(document.getElementById('mp-search').value)"
                        style="border:1px solid #d1d5db;border-radius:8px;padding:8px 10px;font-size:13px;outline:none;background:#fff;cursor:pointer">
                        <option value="">All folders</option>
                        <option value="products">products</option>
                        <option value="banners">banners</option>
                        <option value="media">media</option>
                        <option value="settings">settings</option>
                    </select>
                </div>
                <div class="mp-grid" id="mp-grid">
                    <div class="mp-loading"><i class="fas fa-spinner fa-spin" style="font-size:24px"></i></div>
                </div>
                <div class="mp-footer">
                    <span id="mp-selected-info" style="font-size:12px;color:#6b7280">No image selected</span>
                    <div style="display:flex;gap:8px">
                        <button onclick="closeMediaPicker()"
                            style="background:#f3f4f6;border:none;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;color:#374151">Cancel</button>
                        <button id="mp-select-btn" onclick="mpConfirmSelect()" disabled
                            style="background:var(--teal);color:#fff;border:none;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;opacity:.5">
                            Select Image
                        </button>
                    </div>
                </div>
            </div>

            
            <div id="mp-upload" class="mp-body" style="display:none">
                <div class="mp-upload-area">
                    <div class="mp-dropzone" id="mp-dropzone" onclick="document.getElementById('mp-file-input').click()"
                        ondragover="event.preventDefault();this.classList.add('drag-over')"
                        ondragleave="this.classList.remove('drag-over')"
                        ondrop="event.preventDefault();this.classList.remove('drag-over');mpHandleDrop(event.dataTransfer.files)">
                        <i class="fas fa-cloud-upload-alt"
                            style="font-size:36px;color:#9ca3af;display:block;margin-bottom:10px"></i>
                        <p style="font-weight:600;color:#374151;margin-bottom:4px">Drop images here or click to browse</p>
                        <p style="font-size:12px;color:#9ca3af">JPG, PNG, WebP · Max 5MB each · Multiple allowed</p>
                    </div>
                    <input type="file" id="mp-file-input" multiple accept="image/*" style="display:none"
                        onchange="mpHandleDrop(this.files)">

                    <div class="mp-queue" id="mp-upload-queue" style="display:none"></div>

                    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                        <select id="mp-upload-folder"
                            style="border:1px solid #d1d5db;border-radius:8px;padding:8px 10px;font-size:13px;outline:none;background:#fff">
                            <option value="media">📁 media</option>
                            <option value="products">📁 products</option>
                            <option value="banners">📁 banners</option>
                            <option value="settings">📁 settings</option>
                        </select>
                        <button id="mp-upload-btn" onclick="mpUploadAll()" disabled
                            style="background:var(--teal);color:#fff;border:none;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;opacity:.5">
                            <i class="fas fa-upload mr-1"></i> Upload All
                        </button>
                        <button onclick="mpClearQueue()"
                            style="background:#f3f4f6;border:none;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;color:#374151">
                            Clear
                        </button>
                        <span id="mp-upload-status" style="font-size:12px;color:#6b7280"></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/partials/media-picker.blade.php ENDPATH**/ ?>