<!-- Prescription Modal -->
<div class="modal fade" id="prescriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-plus-medical me-2"></i>Create Prescription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?pg=patient-vitals&patient_id=<?php echo $patient_id; ?>">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="action" value="create_prescription">
                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Drug Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="drug_name" required placeholder="e.g., Lisinopril">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Drug Type</label>
                            <select class="form-select" name="drug_type">
                                <option value="">Select Type</option>
                                <option value="Tablet">Tablet</option>
                                <option value="Capsule">Capsule</option>
                                <option value="Syrup">Syrup</option>
                                <option value="Injection">Injection</option>
                                <option value="Cream">Cream/Ointment</option>
                                <option value="Drops">Drops</option>
                                <option value="Inhaler">Inhaler</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Dosage <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="dosage" required placeholder="e.g., 10mg">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Frequency <span class="text-danger">*</span></label>
                            <select class="form-select" name="frequency" required>
                                <option value="">Select Frequency</option>
                                <option value="Once daily">Once daily</option>
                                <option value="Twice daily">Twice daily</option>
                                <option value="Three times daily">Three times daily</option>
                                <option value="Four times daily">Four times daily</option>
                                <option value="Every 4 hours">Every 4 hours</option>
                                <option value="Every 6 hours">Every 6 hours</option>
                                <option value="Every 8 hours">Every 8 hours</option>
                                <option value="Every 12 hours">Every 12 hours</option>
                                <option value="As needed">As needed</option>
                                <option value="Before meals">Before meals</option>
                                <option value="After meals">After meals</option>
                                <option value="At bedtime">At bedtime</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Duration <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="duration" required placeholder="e.g., 30 days">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Additional Instructions</label>
                        <textarea class="form-control" name="additional_info" rows="3" placeholder="Special instructions, warnings, or notes for the patient..."></textarea>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Note:</strong> Patient will receive a notification about this prescription.
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check me-1"></i> Create Prescription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Appointment Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-calendar-plus me-2"></i>Schedule Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?pg=patient-vitals&patient_id=<?php echo $patient_id; ?>">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="action" value="create_appointment">
                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Appointment Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Appointment Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="appointment_time" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Channel <span class="text-danger">*</span></label>
                            <select class="form-select" name="channel" required onchange="toggleAddressField(this.value)">
                                <option value="">Select Channel</option>
                                <option value="video">Video Call</option>
                                <option value="in-person">In-Person Visit</option>
                                <option value="phone">Phone Call</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duration</label>
                            <select class="form-select" name="duration">
                                <option value="15">15 minutes</option>
                                <option value="30" selected>30 minutes</option>
                                <option value="45">45 minutes</option>
                                <option value="60">1 hour</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3" id="addressField" style="display: none;">
                        <label class="form-label">Location/Address</label>
                        <input type="text" class="form-control" name="address" placeholder="Enter clinic address or meeting location">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Reason for Visit</label>
                        <textarea class="form-control" name="reason" rows="3" placeholder="Brief description of the appointment purpose..."></textarea>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Note:</strong> Patient will receive a notification with appointment details.
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        <i class="bx bx-check me-1"></i> Schedule Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-message-dots me-2"></i>Send Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?pg=patient-vitals&patient_id=<?php echo $patient_id; ?>">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="action" value="send_message">
                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Message Title</label>
                        <input type="text" class="form-control" name="title" placeholder="e.g., Medication Reminder">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="message" rows="5" required placeholder="Type your message to the patient..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Quick Templates</label>
                        <select class="form-select" onchange="fillMessageTemplate(this.value)">
                            <option value="">Select a template...</option>
                            <option value="medication_reminder">Medication Reminder</option>
                            <option value="followup">Follow-up Required</option>
                            <option value="test_results">Test Results Available</option>
                            <option value="appointment_reminder">Appointment Reminder</option>
                            <option value="lifestyle_advice">Lifestyle Advice</option>
                        </select>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-send me-1"></i> Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="bx bx-bell me-2"></i>Send Urgent Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?pg=patient-vitals&patient_id=<?php echo $patient_id; ?>">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="action" value="send_alert">
                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bx bx-error me-2"></i>
                        <strong>Warning:</strong> This will send a high-priority notification to the patient.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alert Type <span class="text-danger">*</span></label>
                        <select class="form-select" name="alert_type" required>
                            <option value="">Select Type</option>
                            <option value="critical_vitals">Critical Vitals Detected</option>
                            <option value="medication_urgent">Urgent Medication Issue</option>
                            <option value="immediate_consultation">Immediate Consultation Required</option>
                            <option value="test_abnormal">Abnormal Test Results</option>
                            <option value="other">Other Urgent Matter</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alert Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="message" rows="4" required placeholder="Describe the urgent matter..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Recommended Action</label>
                        <textarea class="form-control" name="action_required" rows="2" placeholder="What should the patient do? (e.g., Call immediately, Visit ER, etc.)"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bx bx-bell me-1"></i> Send Alert
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPrescriptionModal() {
    new bootstrap.Modal(document.getElementById('prescriptionModal')).show();
}

function openAppointmentModal() {
    new bootstrap.Modal(document.getElementById('appointmentModal')).show();
}

function openMessageModal() {
    new bootstrap.Modal(document.getElementById('messageModal')).show();
}

function openAlertModal() {
    new bootstrap.Modal(document.getElementById('alertModal')).show();
}

function toggleAddressField(channel) {
    const addressField = document.getElementById('addressField');
    if(channel === 'in-person') {
        addressField.style.display = 'block';
        addressField.querySelector('input').required = true;
    } else {
        addressField.style.display = 'none';
        addressField.querySelector('input').required = false;
    }
}

const messageTemplates = {
    'medication_reminder': {
        title: 'Medication Reminder',
        message: 'This is a friendly reminder to take your prescribed medication as directed. Please ensure you follow the dosage and frequency instructions. If you have any questions or concerns, feel free to reach out.'
    },
    'followup': {
        title: 'Follow-up Required',
        message: 'I would like to schedule a follow-up appointment to review your progress and discuss your recent vital readings. Please book an appointment at your earliest convenience.'
    },
    'test_results': {
        title: 'Test Results Available',
        message: 'Your recent test results are now available. I would like to discuss them with you. Please schedule an appointment or call my office to review the results together.'
    },
    'appointment_reminder': {
        title: 'Appointment Reminder',
        message: 'This is a reminder about your upcoming appointment. Please arrive 10 minutes early and bring any relevant medical documents. If you need to reschedule, please contact us as soon as possible.'
    },
    'lifestyle_advice': {
        title: 'Health & Lifestyle Advice',
        message: 'Based on your recent vitals, I recommend focusing on maintaining a healthy lifestyle. This includes regular exercise, a balanced diet, adequate sleep, and stress management. Small changes can make a big difference in your overall health.'
    }
};

function fillMessageTemplate(template) {
    if(!template) return;
    
    const data = messageTemplates[template];
    if(data) {
        document.querySelector('#messageModal input[name="title"]').value = data.title;
        document.querySelector('#messageModal textarea[name="message"]').value = data.message;
    }
}
</script>
