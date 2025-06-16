jQuery(document).ready(function($) {
    // Lấy tất cả các element có class chapter-count
    const chapterCountElements = document.querySelectorAll('.chapter-count');
    
    // Tạo một queue để xử lý từng request một
    const queue = Array.from(chapterCountElements);
    
    // Hàm xử lý một request
    function processNextRequest() {
        if (queue.length === 0) return;
        
        const element = queue.shift();
        const truyenId = element.dataset.truyenId;
        
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'get_chapter_count',
                truyen_id: truyenId,
                nonce: ajax_object.nonce
            },
            success: function(response) {
                if (response.success) {
                    element.textContent = `${response.data.count}`;
                } else {
                    element.textContent = 'Lỗi tải số chương';
                }
                
                // Xử lý request tiếp theo sau 100ms
                setTimeout(processNextRequest, 100);
            },
            error: function() {
                element.textContent = 'Lỗi tải số chương';
                setTimeout(processNextRequest, 100);
            }
        });
    }
    
    // Bắt đầu xử lý queue
    processNextRequest();
}); 