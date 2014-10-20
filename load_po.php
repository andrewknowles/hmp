
INSERT INTO sfh (
bra_id,
dpr_id,
cpy_id,
spp_id,
sor_id,
oer_id,
lng_id,
txg_id,
cur_id,
tad_id_ord,
sfh_doc_id,
sfh_spo_id, 
sfh_d_cre,
sfh_d_closed,
sfh_oprt,
sfh_osort,
sfh_ocopy,
sfh_prt,sfh_reference,
sfh_lock_rate,
sfh_purch_rate,
sfh_cancel, 
sfh_omethod,
sfh_recurring,
sfh_name_ctc,
sfh_phone_ctc, 
sfh_matchname,sfh_registration,sfh_edi_status,ocr_id,sfh_orqi,sfh_fill_rate,sfh_ship_rules,
sfh_measure,sfh_id_rec,sfh_rec_price,sfh_basis,sfh_dir_ship,sfh_axe_1,sfh_axe_2,sfh_axe_3,sfh_axe_4,sft_id, sfh_po_status,sfh_matchphone,tad_id_del,sfh_visa_status,usr_id_open,usr_id_clo,
bra_id_del,dpr_id_del,csc_id_del,sfh_ship_delay,sfh_transfer,sfh_shp_group,sfh_receipt_rules 
) VALUES (
'1HEB',
'300',
'HMUK',
'80023',
'EMG',
NULL,
'0',
'SIMEEC',
'EUR',
133,
NULL,
NULL,
'2014-8-5 16:44:55',
NULL,
1,
1,
1,
0,
NULL,
0,
1,215,
0,
'08',
0,
NULL,
'31 1620 84400',
'HITACHI CONSTRUCTION MACHINERY (EUR) NV',
'NL 0013 23 325 B 01',0,'DHLTK',1,0,NULL,1,NULL,0,1,0,NULL,NULL,NULL,'IC','301',0,'31162084400',9,0,'0000',NULL,'1HEB','300',NULL,'05',0,2,0);


select * from sfl where sfh_id = ? and sfl_id = ? -- ADD
-- sfh_id#0#4
-- sfl_id#0#1
-- pmf_id##HCMP
-- pro_id##0003076
-- txr_id##0
-- psa_id##
-- psf_id##
-- afm_id##PARTS
-- pcc_id##
-- cpy_id##HMUK
-- spp_id##80023
-- sor_id##EMG
-- sfl_line##1
-- sfl_desc##CASING PUMP
-- sfl_eec_partlist##
-- sfl_weight##0
-- sfl_weight_unit##0
-- sfl_upurch##
-- sfl_set#0#1
-- sfl_source_code##
-- sfl_d_cre#30/11/1999#05/08/2014 16:45:03
-- sfl_d_due#30/11/1999#01/01/1900
-- sfl_leadtime_spp#0#0
-- sfl_price##1361,8
-- sfl_d1##0
-- sfl_r1##0
-- sfl_d2##0
-- sfl_r2##0
-- sfl_d3##0
-- sfl_r3##0
-- sfl_d4##0
-- sfl_r4##0
-- sfl_d5##0
-- sfl_r5##0
-- sfl_pnet_cur##1361,8
-- sfl_pnet_euro##0
-- sfl_pnet_local##1120,82
-- sfl_cost##1120,82
-- sfl_q_sord##1
-- sfl_q_scan##0
-- sfl_q_srec##0
-- sfl_d_required#30/11/1999#01/01/1900
-- sfl_d_promise#30/11/1999#01/01/1900
-- sfl_d_rec_first#30/11/1999#01/01/1900
-- sfl_d_rec_last#30/11/1999#01/01/1900
-- sfl_fill_rate##0
-- sfl_reason##
-- pmf_id_stored##HCMP
-- pro_id_stored##0003076
-- sfl_loc_n##0
-- sfl_desc_spp##
-- sfl_packpurch##1
-- sfl_upcoef##1
-- sfl_packsale##1
-- sfl_managed##1
-- sfl_id_master#0#0
-- equ_id#0#0
-- pmf_id_spg##
-- spg_id##
-- oer_id##
-- sfl_disc##0
-- sfl_id_attach#0#0
-- sfl_assoc##0
-- sfl_attach##0
-- sfl_hmvt##1
-- sfl_consump##1
-- egr_id##
-- esr_id##
-- sfl_serialized##0
-- sfl_usale##
-- sfl_desc_upd##1
-- sfl_qtydec##0
-- sfl_epl_type##0
-- cur_id_origin##
-- sfl_mfr_orig_price##0
-- pmf_id_prompt##
-- pro_id_prompt##
-- equ_id_prompt#0#0
-- sfl_attach_receipt##
-- ean_cl_id##
-- ean_sc_id##
-- equ_id_cost#0#
-- sfl_reman_return##0