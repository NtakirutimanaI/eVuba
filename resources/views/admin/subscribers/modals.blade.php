<!-- SEND UPDATE MODAL -->
<div id="sendModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-paper-plane" style="color: var(--primary);"></i> Draft Global Update</h3>
            <span class="close-modal" id="closeSendModal">&times;</span>
        </div>
        <form action="{{ route('admin.subscribers.sendUpdate') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Communication Message</label>
                <textarea name="message" class="form-control" rows="6" placeholder="Write your announcement or update here..." required></textarea>
                <p style="font-size: 11px; color: #94a3b8; margin-top: 8px;">Note: This message will be sent to the selected segments below.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Target Audience Selection</label>
                <div style="max-height: 150px; overflow-y: auto; background: #f8fafc; border-radius: 12px; padding: 15px; border: 1px solid #e2e8f0;">
                    @foreach($subscribers as $subscriber)
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <input type="checkbox" name="subscribers[]" value="{{ $subscriber->id }}" id="modal-sub{{ $subscriber->id }}" style="width: 16px; height: 16px;">
                        <label for="modal-sub{{ $subscriber->id }}" style="font-size: 13px; color: #475569;">{{ $subscriber->email }}</label>
                    </div>
                    @endforeach
                </div>
                <p style="font-size: 11px; color: #64748b; margin-top: 5px;">Leave unchecked to send to <strong>everyone</strong> ({{ $subscribers->count() }} subscribers).</p>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 14px; border-radius: 12px;">
                <i class="fas fa-bolt"></i> Send Announcements Now
            </button>
        </form>
    </div>
</div>

<!-- VIEW DETAILS MODAL -->
<div id="viewModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header">
            <h3 class="modal-title">Subscriber Profile</h3>
            <span class="close-modal" id="closeViewModal">&times;</span>
        </div>
        <div style="background: #f8fafc; border-radius: 20px; padding: 30px; border: 1px solid #e2e8f0; text-align: center;">
            <div style="width: 80px; height: 80px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; border: 4px solid #f1f5f9; box-shadow: var(--shadow-md);">
                <i class="fas fa-user-circle" style="font-size: 48px; color: var(--primary);"></i>
            </div>
            <h4 id="viewEmail" style="margin: 0; font-size: 18px; color: #1e293b; font-weight: 700;">-</h4>
            <div id="viewId" style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-top: 5px; letter-spacing: 1px;">-</div>
            
            <div style="margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 20px; text-align: left;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <span style="font-size: 13px; color: #64748b;"><i class="fas fa-calendar-alt" style="margin-right: 8px;"></i> Subscribed Since:</span>
                    <span id="viewCreated" style="font-size: 13px; font-weight: 700; color: #334155;">-</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="font-size: 13px; color: #64748b;"><i class="fas fa-check-circle" style="margin-right: 8px;"></i> Account Status:</span>
                    <span style="font-size: 11px; font-weight: 800; color: white; background: var(--success); padding: 3px 12px; border-radius: 20px; text-transform: uppercase;">Active</span>
                </div>
            </div>
        </div>
    </div>
</div>
