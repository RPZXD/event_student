<!-- QR Code Generator Page - OPTIMIZED VERSION -->
<?php
// Data passed from controller: $activity, $codes, $totalCodes, $totalPages, $page, $offset, $activity_id
$baseUrl = 'https://eventstd.phichai.ac.th/checkin.php?code=';

// Pre-generate QR URLs for current page codes using goqr.me API (free & reliable)
$qrUrls = [];
foreach ($codes as $code) {
    // QR Server API - fast and free
    $qrUrls[$code['id']] = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($baseUrl . $code['code']);
}
?>
<div class="space-y-4 sm:space-y-6 lg:space-y-8">
    
    <!-- Header Section -->
    <div class="glass rounded-xl sm:rounded-2xl p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl text-white text-xl shadow-lg">
                    🔗
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">🎯 <?= htmlspecialchars($activity['title']) ?></h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">จัดการ QR Code กิจกรรม</p>
                </div>
            </div>
            <a href="create_event.php" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>กลับ</span>
            </a>
        </div>
    </div>

    <!-- Activity Info -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="glass rounded-xl p-4 text-center">
            <div class="text-2xl mb-2">📅</div>
            <p class="text-xs text-gray-500 dark:text-gray-400">วันที่จัด</p>
            <p class="font-semibold text-gray-900 dark:text-white"><?= date('d/m/Y', strtotime($activity['event_date'])) ?></p>
        </div>
        <div class="glass rounded-xl p-4 text-center">
            <div class="text-2xl mb-2">⏰</div>
            <p class="text-xs text-gray-500 dark:text-gray-400">ชั่วโมง</p>
            <p class="font-semibold text-gray-900 dark:text-white"><?= $activity['hours'] ?> ชม.</p>
        </div>
        <div class="glass rounded-xl p-4 text-center">
            <div class="text-2xl mb-2">👥</div>
            <p class="text-xs text-gray-500 dark:text-gray-400">จำนวนสูงสุด</p>
            <p class="font-semibold text-gray-900 dark:text-white"><?= $activity['max_students'] ?> คน</p>
        </div>
        <div class="glass rounded-xl p-4 text-center">
            <div class="text-2xl mb-2">📊</div>
            <p class="text-xs text-gray-500 dark:text-gray-400">จำนวนโค้ด</p>
            <p class="font-semibold text-gray-900 dark:text-white"><?= $totalCodes ?> โค้ด</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="glass rounded-xl p-4 sm:p-6">
        <div class="flex flex-wrap gap-2">
            <button id="download-excel" class="btn-success px-4 py-2 rounded-lg text-white font-medium flex items-center gap-2">
                <i class="fas fa-file-excel"></i>
                <span class="hidden sm:inline">ดาวน์โหลด Excel</span>
                <span class="sm:hidden">Excel</span>
            </button>
            <button id="print-current-page" class="btn-primary px-4 py-2 rounded-lg text-white font-medium flex items-center gap-2">
                <i class="fas fa-print"></i>
                <span class="hidden sm:inline">พิมพ์หน้านี้</span>
                <span class="sm:hidden">พิมพ์</span>
            </button>
            <button id="print-all-qrcodes" class="bg-indigo-800 hover:bg-indigo-900 px-4 py-2 rounded-lg text-white font-medium flex items-center gap-2 transition-all">
                <i class="fas fa-print"></i>
                <span class="hidden sm:inline">พิมพ์ทั้งหมด</span>
                <span class="sm:hidden">พิมพ์ทั้งหมด</span>
            </button>
        </div>
    </div>

    <!-- Pagination Info -->
    <?php if ($totalPages > 1): ?>
    <div class="glass rounded-xl p-4 text-center">
        <div class="flex flex-wrap justify-center gap-2 mb-3">
            <?php if ($page > 1): ?>
                <a href="?activity_id=<?= $activity_id ?>&page=<?= $page - 1 ?>" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    « ก่อนหน้า
                </a>
            <?php endif; ?>
            
            <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);
            for ($i = $start; $i <= $end; $i++):
            ?>
                <a href="?activity_id=<?= $activity_id ?>&page=<?= $i ?>" 
                   class="px-4 py-2 rounded-lg transition-colors <?= $i == $page ? 'bg-indigo-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="?activity_id=<?= $activity_id ?>&page=<?= $page + 1 ?>" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    ถัดไป »
                </a>
            <?php endif; ?>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            หน้า <?= $page ?> จาก <?= $totalPages ?> (แสดง <?= count($codes) ?> จาก <?= $totalCodes ?> รายการ)
        </p>
    </div>
    <?php endif; ?>

    <!-- QR Codes Grid - INSTANT LOADING with Google Charts -->
    <div class="glass rounded-xl sm:rounded-2xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-violet-500 to-purple-600 rounded-lg text-white text-lg">
                    📄
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">รายการ QR Codes</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">⚡ โหลดทันทีด้วย Google Charts API</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 sm:p-6">
            <!-- QR Code Grid - Responsive -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                <?php foreach ($codes as $index => $code): ?>
                <div class="qr-card bg-white dark:bg-slate-700 rounded-xl p-3 shadow-sm border border-gray-100 dark:border-gray-600 text-center hover:shadow-md transition-shadow">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">#<?= $offset + $index + 1 ?></div>
                    <!-- Use Google Charts API for instant QR loading -->
                    <img src="<?= $qrUrls[$code['id']] ?>" 
                         alt="QR Code" 
                         class="w-20 h-20 mx-auto mb-2 bg-white rounded"
                         loading="lazy"
                         data-code="<?= htmlspecialchars($code['code']) ?>">
                    <div class="text-xs font-mono text-gray-600 dark:text-gray-400 truncate" title="<?= htmlspecialchars($code['code']) ?>"><?= htmlspecialchars($code['code']) ?></div>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs <?= $code['is_used'] ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' ?>">
                        <?= $code['is_used'] ? 'ใช้แล้ว' : 'ยังไม่ใช้' ?>
                    </span>
                    <!-- Action buttons -->
                    <div class="flex gap-1 justify-center mt-2">
                        <button class="copy-code px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs transition-colors" data-code="<?= htmlspecialchars($code['code']) ?>" title="คัดลอก">
                            <i class="fas fa-copy"></i>
                        </button>
                        <a href="<?= $qrUrls[$code['id']] ?>" download="<?= $code['code'] ?>.png" class="px-2 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs transition-colors" title="ดาวน์โหลด">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div id="progress-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
            <span id="progress-title">กำลังเตรียมข้อมูล...</span>
        </h3>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-2">
            <div id="progress-bar" class="bg-gradient-to-r from-indigo-500 to-purple-500 h-3 rounded-full transition-all duration-100" style="width: 0%"></div>
        </div>
        <div id="progress-text" class="text-center text-sm text-gray-600 dark:text-gray-400">0%</div>
    </div>
</div>

<!-- Print Layout Container (Hidden) -->
<div id="print-container" class="print-only-layout"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const activityTitle = <?= json_encode($activity['title']) ?>;
    const activityId = <?= $activity_id ?>;
    const baseUrl = 'https://eventstd.phichai.ac.th/checkin.php?code=';
    const qrApiBase = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=';

    // Copy code
    document.querySelectorAll('.copy-code').forEach(btn => {
        btn.addEventListener('click', function() {
            navigator.clipboard.writeText(this.dataset.code).then(() => {
                Swal.fire({ title: 'คัดลอกสำเร็จ!', icon: 'success', timer: 1000, showConfirmButton: false });
            });
        });
    });

    // Download Excel
    document.getElementById('download-excel').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const response = await fetch(`../controllers/EventController.php?activity_id=${activityId}&all_codes=1`);
            const result = await response.json();
            
            if (result.codes) {
                const data = [['โค้ด 1', 'โค้ด 2', 'โค้ด 3', 'โค้ด 4', 'โค้ด 5']];
                const allCodes = result.codes.map(c => c.code);
                
                for (let i = 0; i < allCodes.length; i += 5) {
                    data.push([allCodes[i]||'', allCodes[i+1]||'', allCodes[i+2]||'', allCodes[i+3]||'', allCodes[i+4]||'']);
                }

                const ws = XLSX.utils.aoa_to_sheet(data);
                ws['!cols'] = [{wch:15},{wch:15},{wch:15},{wch:15},{wch:15}];
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'QR Codes');
                XLSX.writeFile(wb, activityTitle + '_QRCodes.xlsx');
            }
        } catch (e) {
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถดาวน์โหลดได้', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-file-excel"></i> <span class="hidden sm:inline">ดาวน์โหลด Excel</span><span class="sm:hidden">Excel</span>';
        }
    });

    // Print current page - INSTANT (images already loaded)
    document.getElementById('print-current-page').addEventListener('click', function() {
        const images = document.querySelectorAll('.qr-card img');
        let html = `<div class="print-header"><h2>${activityTitle} - หน้า <?= $page ?></h2></div><div class="qr-grid">`;
        
        images.forEach(img => {
            html += `<div class="qr-item"><img src="${img.src}"><div class="qr-code-text">${img.dataset.code}</div></div>`;
        });
        
        html += '</div>';
        document.getElementById('print-container').innerHTML = html;
        setTimeout(() => window.print(), 100);
    });

    // Print all - Load images in batches and wait for all to complete
    document.getElementById('print-all-qrcodes').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังเตรียม...';
        
        showProgress('กำลังดึงข้อมูล...');
        
        try {
            const response = await fetch(`../controllers/EventController.php?activity_id=${activityId}&all_codes=1`);
            const result = await response.json();
            
            if (result.codes && result.codes.length > 0) {
                updateProgressTitle('กำลังโหลด QR Codes...');
                
                const totalCodes = result.codes.length;
                const batchSize = 30; // Load 30 images at a time to avoid overwhelming the server
                const imageData = [];
                
                // Load images in batches
                for (let batchStart = 0; batchStart < totalCodes; batchStart += batchSize) {
                    const batchEnd = Math.min(batchStart + batchSize, totalCodes);
                    const batchCodes = result.codes.slice(batchStart, batchEnd);
                    
                    // Create promises for this batch
                    const batchPromises = batchCodes.map(c => {
                        return new Promise((resolve) => {
                            const img = new Image();
                            img.crossOrigin = 'anonymous';
                            
                            img.onload = () => {
                                imageData.push({ code: c.code, src: img.src, loaded: true });
                                resolve();
                            };
                            
                            img.onerror = () => {
                                // Retry once
                                const retryImg = new Image();
                                retryImg.crossOrigin = 'anonymous';
                                retryImg.onload = () => {
                                    imageData.push({ code: c.code, src: retryImg.src, loaded: true });
                                    resolve();
                                };
                                retryImg.onerror = () => {
                                    imageData.push({ code: c.code, src: qrApiBase + encodeURIComponent(baseUrl + c.code), loaded: false });
                                    resolve();
                                };
                                retryImg.src = qrApiBase + encodeURIComponent(baseUrl + c.code);
                            };
                            
                            img.src = qrApiBase + encodeURIComponent(baseUrl + c.code);
                        });
                    });
                    
                    // Wait for this batch to complete
                    await Promise.all(batchPromises);
                    
                    // Update progress
                    const progress = Math.min(95, (batchEnd / totalCodes) * 95);
                    updateProgress(progress);
                }
                
                updateProgressTitle('กำลังเตรียมหน้าพิมพ์...');
                updateProgress(98);
                
                // Sort imageData to match original order
                const codeOrder = result.codes.map(c => c.code);
                imageData.sort((a, b) => codeOrder.indexOf(a.code) - codeOrder.indexOf(b.code));
                
                // Build print HTML
                let html = `<div class="print-header"><h2>${activityTitle} - ทั้งหมด (${totalCodes} รายการ)</h2></div><div class="qr-grid">`;
                
                imageData.forEach(item => {
                    html += `<div class="qr-item"><img src="${item.src}"><div class="qr-code-text">${item.code}</div></div>`;
                });
                
                html += '</div>';
                document.getElementById('print-container').innerHTML = html;
                
                updateProgress(100);
                
                // Small delay to ensure DOM is ready
                await new Promise(r => setTimeout(r, 300));
                
                hideProgress();
                window.print();
            }
        } catch (e) {
            hideProgress();
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถดึงข้อมูลได้: ' + e.message, 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-print"></i> <span class="hidden sm:inline">พิมพ์ทั้งหมด</span><span class="sm:hidden">พิมพ์ทั้งหมด</span>';
        }
    });

    function showProgress(title) {
        document.getElementById('progress-title').textContent = title;
        document.getElementById('progress-modal').classList.remove('hidden');
    }
    
    function updateProgressTitle(title) {
        document.getElementById('progress-title').textContent = title;
    }
    
    function hideProgress() {
        document.getElementById('progress-modal').classList.add('hidden');
    }
    
    function updateProgress(percent) {
        document.getElementById('progress-bar').style.width = percent + '%';
        document.getElementById('progress-text').textContent = Math.round(percent) + '%';
    }
});
</script>

<style>
.print-only-layout { display: none; }

@media print {
    body * { visibility: hidden !important; }
    .print-only-layout, .print-only-layout * { visibility: visible !important; display: block !important; }
    .print-only-layout {
        position: absolute !important; left: 0 !important; top: 0 !important;
        width: 100% !important; padding: 8mm !important; background: white !important;
    }
    .print-header { text-align: center !important; margin-bottom: 8mm !important; }
    .print-header h2 { font-size: 16pt !important; font-weight: bold !important; color: black !important; }
    .qr-grid { display: grid !important; grid-template-columns: repeat(5, 1fr) !important; gap: 3mm !important; }
    .qr-item {
        display: flex !important; flex-direction: column !important; align-items: center !important;
        page-break-inside: avoid !important; padding: 2mm !important; border: 1px solid #ddd !important;
    }
    .qr-item img { width: 25mm !important; height: 25mm !important; margin-bottom: 1mm !important; }
    .qr-code-text { font-size: 7pt !important; font-family: monospace !important; color: black !important; }
    @page { margin: 5mm; size: A4; }
}
</style>
