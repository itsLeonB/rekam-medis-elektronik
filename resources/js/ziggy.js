const Ziggy = {"url":"http:\/\/localhost:8000","port":8000,"defaults":{},"routes":{"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"ignition.healthCheck":{"uri":"_ignition\/health-check","methods":["GET","HEAD"]},"ignition.executeSolution":{"uri":"_ignition\/execute-solution","methods":["POST"]},"ignition.updateConfig":{"uri":"_ignition\/update-config","methods":["POST"]},"telescope":{"uri":"telescope\/{view?}","methods":["GET","HEAD"],"wheres":{"view":"(.*)"},"parameters":["view"]},"home.index":{"uri":"home","methods":["GET","HEAD"]},"rawatjalan":{"uri":"rawat-jalan","methods":["GET","HEAD"]},"rawatjalan.daftar":{"uri":"rawat-jalan\/daftar","methods":["GET","HEAD"]},"rawatjalan.details":{"uri":"rawat-jalan\/details\/{encounter_satusehat_id}","methods":["GET","HEAD"],"parameters":["encounter_satusehat_id"]},"rawatinap":{"uri":"rawat-inap","methods":["GET","HEAD"]},"rawatinap.daftar":{"uri":"rawat-inap\/daftar","methods":["GET","HEAD"]},"rawatinap.details":{"uri":"rawat-inap\/details\/{encounter_satusehat_id}","methods":["GET","HEAD"],"parameters":["encounter_satusehat_id"]},"gawatdarurat":{"uri":"gawat-darurat","methods":["GET","HEAD"]},"rekammedis":{"uri":"rekam-medis-pasien","methods":["GET","HEAD"]},"rekammedis.details":{"uri":"rekam-medis-pasien\/details\/{patient_satusehat_id}","methods":["GET","HEAD"],"parameters":["patient_satusehat_id"]},"rekammedis.tambah":{"uri":"rekam-medis-pasien\/daftar","methods":["GET","HEAD"]},"gawatdarurat.daftar":{"uri":"gawat-darurat\/daftar","methods":["GET","HEAD"]},"usermanagement":{"uri":"user-management","methods":["GET","HEAD"]},"usermanagement.details":{"uri":"user-management\/details\/{user_id}","methods":["GET","HEAD"],"parameters":["user_id"]},"usermanagement.tambah":{"uri":"user-management\/tambah-user","methods":["GET","HEAD"]},"medicinetransaction":{"uri":"medicine-transaction","methods":["GET","HEAD"]},"medicinetransaction.tambah":{"uri":"medicine-transaction\/form-transaction","methods":["GET","HEAD"]},"medication.table":{"uri":"medication\/table","methods":["GET","HEAD"]},"medication.details":{"uri":"medication\/details","methods":["GET","HEAD"]},"medication.tambah":{"uri":"medication\/tambah","methods":["GET","HEAD"]},"medication":{"uri":"medication","methods":["GET","HEAD"]},"medication.prescription":{"uri":"medication\/prescription","methods":["GET","HEAD"]},"medicationDispense.details":{"uri":"medication-dispense\/details\/{medication_dispense_id}","methods":["GET","HEAD"],"parameters":["medication_dispense_id"]},"medication.requestStock":{"uri":"medication\/request-stock","methods":["GET","HEAD"]},"profile.edit":{"uri":"profile","methods":["GET","HEAD"]},"profile.update":{"uri":"profile","methods":["PATCH"]},"profile.destroy":{"uri":"profile","methods":["DELETE"]},"profile.details":{"uri":"profile\/details","methods":["GET","HEAD"]},"integration.show":{"uri":"integration\/{resourceType}\/{id}","methods":["GET","HEAD"],"parameters":["resourceType","id"]},"integration.store":{"uri":"integration\/{resourceType}","methods":["POST"],"parameters":["resourceType"]},"integration.update":{"uri":"integration\/{resourceType}\/{id}","methods":["PUT"],"parameters":["resourceType","id"]},"rekam-medis.index":{"uri":"rekam-medis","methods":["GET","HEAD"]},"rekam-medis.show":{"uri":"rekam-medis\/{patient_id}","methods":["GET","HEAD"],"parameters":["patient_id"]},"rekam-medis.update":{"uri":"rekam-medis\/{patient_id}\/update","methods":["GET","HEAD"],"parameters":["patient_id"]},"daftar-pasien.rawat-jalan.umum":{"uri":"daftar-pasien\/rawat-jalan\/umum","methods":["GET","HEAD"]},"daftar-pasien.rawat-jalan.neurologi":{"uri":"daftar-pasien\/rawat-jalan\/neurologi","methods":["GET","HEAD"]},"daftar-pasien.rawat-jalan.obgyn":{"uri":"daftar-pasien\/rawat-jalan\/obgyn","methods":["GET","HEAD"]},"daftar-pasien.rawat-jalan.gigi":{"uri":"daftar-pasien\/rawat-jalan\/gigi","methods":["GET","HEAD"]},"daftar-pasien.rawat-jalan.kulit":{"uri":"daftar-pasien\/rawat-jalan\/kulit","methods":["GET","HEAD"]},"daftar-pasien.rawat-jalan.ortopedi":{"uri":"daftar-pasien\/rawat-jalan\/ortopedi","methods":["GET","HEAD"]},"daftar-pasien.rawat-jalan.dalam":{"uri":"daftar-pasien\/rawat-jalan\/dalam","methods":["GET","HEAD"]},"daftar-pasien.rawat-jalan.bedah":{"uri":"daftar-pasien\/rawat-jalan\/bedah","methods":["GET","HEAD"]},"daftar-pasien.rawat-jalan.anak":{"uri":"daftar-pasien\/rawat-jalan\/anak","methods":["GET","HEAD"]},"daftar-pasien.rawat-inap":{"uri":"daftar-pasien\/rawat-inap","methods":["GET","HEAD"]},"daftar-pasien.igd":{"uri":"daftar-pasien\/igd","methods":["GET","HEAD"]},"obat.index":{"uri":"obat","methods":["GET","HEAD"]},"obat.requestStock":{"uri":"obat\/data-request-stock","methods":["GET","HEAD"]},"obat.checkRequest":{"uri":"obat\/check-request-stock","methods":["GET","HEAD"]},"obat.requestStock.delete":{"uri":"obat\/delete-request-stok\/{code}","methods":["DELETE"],"parameters":["code"]},"obat.show":{"uri":"obat\/{medication_id}","methods":["GET","HEAD"],"parameters":["medication_id"]},"obat.update":{"uri":"obat\/{medication_id}\/update","methods":["GET","HEAD"],"parameters":["medication_id"]},"obat.store":{"uri":"obat\/obat","methods":["POST"]},"medication.rawat-jalan.umum":{"uri":"medication\/rawat-jalan\/umum","methods":["GET","HEAD"]},"medication.rawat-jalan.neurologi":{"uri":"medication\/rawat-jalan\/neurologi","methods":["GET","HEAD"]},"medication.rawat-jalan.obgyn":{"uri":"medication\/rawat-jalan\/obgyn","methods":["GET","HEAD"]},"medication.rawat-jalan.gigi":{"uri":"medication\/rawat-jalan\/gigi","methods":["GET","HEAD"]},"medication.rawat-jalan.kulit":{"uri":"medication\/rawat-jalan\/kulit","methods":["GET","HEAD"]},"medication.rawat-jalan.ortopedi":{"uri":"medication\/rawat-jalan\/ortopedi","methods":["GET","HEAD"]},"medication.rawat-jalan.dalam":{"uri":"medication\/rawat-jalan\/dalam","methods":["GET","HEAD"]},"medication.rawat-jalan.bedah":{"uri":"medication\/rawat-jalan\/bedah","methods":["GET","HEAD"]},"medication.rawat-jalan.anak":{"uri":"medication\/rawat-jalan\/anak","methods":["GET","HEAD"]},"medication.igd":{"uri":"medication\/igd","methods":["GET","HEAD"]},"medication.rekammedis.details":{"uri":"medication\/medication\/details\/{code_medication}","methods":["GET","HEAD"],"parameters":["code_medication"]},"analytics.pasien-dirawat":{"uri":"analytics\/pasien-dirawat","methods":["GET","HEAD"]},"analytics.pasien-baru-bulan-ini":{"uri":"analytics\/pasien-baru-bulan-ini","methods":["GET","HEAD"]},"analytics.jumlah-pasien":{"uri":"analytics\/jumlah-pasien","methods":["GET","HEAD"]},"analytics.pasien-per-bulan":{"uri":"analytics\/pasien-per-bulan","methods":["GET","HEAD"]},"analytics.sebaran-usia-pasien":{"uri":"analytics\/sebaran-usia-pasien","methods":["GET","HEAD"]},"analytics.obat-mendekati-kadaluarsa":{"uri":"analytics\/obat-mendekati-kadaluarsa","methods":["GET","HEAD"]},"analytics.obat-stok-sedikit":{"uri":"analytics\/obat-stok-sedikit","methods":["GET","HEAD"]},"analytics.obat-fast-moving":{"uri":"analytics\/obat-fast-moving","methods":["GET","HEAD"]},"analytics.obat-penggunaan-paling-banyak":{"uri":"analytics\/obat-penggunaan-paling-banyak","methods":["GET","HEAD"]},"analytics.obat-transaksi-perbandingan-per-bulan":{"uri":"analytics\/obat-transaksi-perbandingan-per-bulan","methods":["GET","HEAD"]},"analytics.obat-persebaran-stok":{"uri":"analytics\/obat-persebaran-stok","methods":["GET","HEAD"]},"analytics.forecast":{"uri":"analytics\/forecast","methods":["GET","HEAD"]},"analytics.save-monthly-data":{"uri":"analytics\/save-monthly-data","methods":["POST"]},"analytics.fetch-forecast":{"uri":"analytics\/fetch-forecast","methods":["GET","HEAD"]},"form.index.encounter":{"uri":"form\/daftar\/practitioner","methods":["GET","HEAD"]},"form.index.location":{"uri":"form\/daftar\/location","methods":["GET","HEAD"]},"form.ref.organization":{"uri":"form\/ref\/organization\/{layanan}","methods":["GET","HEAD"],"parameters":["layanan"]},"form.ref.identifier":{"uri":"form\/ref\/identifier\/{layanan}\/{res_type}","methods":["GET","HEAD"],"parameters":["layanan","res_type"]},"kunjungan":{"uri":"kunjungan\/{resType}\/{encounterId}","methods":["GET","HEAD"],"parameters":["resType","encounterId"]},"users.index":{"uri":"users","methods":["GET","HEAD"]},"users.show":{"uri":"users\/{user_id}","methods":["GET","HEAD"],"parameters":["user_id"]},"users.store":{"uri":"users","methods":["POST"]},"users.update":{"uri":"users\/{user_id}","methods":["PUT"],"parameters":["user_id"]},"users.destroy":{"uri":"users\/{user_id}","methods":["DELETE"],"parameters":["user_id"]},"users.roles":{"uri":"users\/get\/roles","methods":["GET","HEAD"]},"medicinetransactions.index":{"uri":"medicinetransactions","methods":["GET","HEAD"]},"medicinetransactions.show":{"uri":"medicinetransactions\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"medicinetransactions.store":{"uri":"medicinetransactions","methods":["POST"]},"medicinetransactions.update":{"uri":"medicinetransactions\/{id}","methods":["PUT"],"parameters":["id"]},"medicinetransactions.destroy":{"uri":"medicinetransactions\/{id}","methods":["DELETE"],"parameters":["id"]},"medicinetransactions.roles":{"uri":"medicinetransactions\/get\/roles","methods":["GET","HEAD"]},"getmedicine":{"uri":"getmedicine","methods":["GET","HEAD"]},"terminologi.get":{"uri":"terminologi\/get","methods":["GET","HEAD"]},"terminologi.procedure.tindakan":{"uri":"terminologi\/procedure\/tindakan","methods":["GET","HEAD"]},"terminologi.procedure.edukasi-bayi":{"uri":"terminologi\/procedure\/edukasi-bayi","methods":["GET","HEAD"]},"terminologi.procedure.other":{"uri":"terminologi\/procedure\/other","methods":["GET","HEAD"]},"terminologi.condition.kunjungan":{"uri":"terminologi\/condition\/kunjungan","methods":["GET","HEAD"]},"terminologi.condition.keluar":{"uri":"terminologi\/condition\/keluar","methods":["GET","HEAD"]},"terminologi.condition.keluhan":{"uri":"terminologi\/condition\/keluhan","methods":["GET","HEAD"]},"terminologi.condition.riwayat-pribadi":{"uri":"terminologi\/condition\/riwayat-pribadi","methods":["GET","HEAD"]},"terminologi.condition.riwayat-keluarga":{"uri":"terminologi\/condition\/riwayat-keluarga","methods":["GET","HEAD"]},"terminologi.questionnaire.lokasi-kecelakaan":{"uri":"terminologi\/questionnaire\/lokasi-kecelakaan","methods":["GET","HEAD"]},"terminologi.questionnaire.poli-tujuan":{"uri":"terminologi\/questionnaire\/poli-tujuan","methods":["GET","HEAD"]},"terminologi.questionnaire.other":{"uri":"terminologi\/questionnaire\/other","methods":["GET","HEAD"]},"terminologi.medication":{"uri":"terminologi\/medication","methods":["GET","HEAD"]},"terminologi.ingridients":{"uri":"terminologi\/ingridients","methods":["GET","HEAD"]},"terminologi.icd10":{"uri":"terminologi\/icd10","methods":["GET","HEAD"]},"terminologi.icd9cm-procedure":{"uri":"terminologi\/icd9cm-procedure","methods":["GET","HEAD"]},"terminologi.loinc":{"uri":"terminologi\/loinc","methods":["GET","HEAD"]},"terminologi.snomed-ct":{"uri":"terminologi\/snomed-ct","methods":["GET","HEAD"]},"terminologi.wilayah.provinsi":{"uri":"terminologi\/wilayah\/provinsi","methods":["GET","HEAD"]},"terminologi.wilayah.kabko":{"uri":"terminologi\/wilayah\/kabko","methods":["GET","HEAD"]},"terminologi.wilayah.kotalahir":{"uri":"terminologi\/wilayah\/kotalahir","methods":["GET","HEAD"]},"terminologi.wilayah.kecamatan":{"uri":"terminologi\/wilayah\/kecamatan","methods":["GET","HEAD"]},"terminologi.wilayah.kelurahan":{"uri":"terminologi\/wilayah\/kelurahan","methods":["GET","HEAD"]},"terminologi.bcp13":{"uri":"terminologi\/bcp13","methods":["GET","HEAD"]},"terminologi.bcp47":{"uri":"terminologi\/bcp47","methods":["GET","HEAD"]},"terminologi.iso3166":{"uri":"terminologi\/iso3166","methods":["GET","HEAD"]},"terminologi.ucum":{"uri":"terminologi\/ucum","methods":["GET","HEAD"]},"satusehat.consent.show":{"uri":"satusehat\/consent\/{patient_id}","methods":["GET","HEAD"],"parameters":["patient_id"]},"satusehat.consent.store":{"uri":"satusehat\/consent","methods":["POST"]},"satusehat.kfa":{"uri":"satusehat\/kfa","methods":["GET","HEAD"]},"satusehat.search.practitioner":{"uri":"satusehat\/search\/practitioner","methods":["GET","HEAD"]},"satusehat.search.organization":{"uri":"satusehat\/search\/organization","methods":["GET","HEAD"]},"satusehat.search.location":{"uri":"satusehat\/search\/location","methods":["GET","HEAD"]},"satusehat.search.patient":{"uri":"satusehat\/search\/patient","methods":["GET","HEAD"]},"satusehat.search.condition":{"uri":"satusehat\/search\/condition","methods":["GET","HEAD"]},"satusehat.search.medicationrequest":{"uri":"satusehat\/search\/medicationrequest","methods":["GET","HEAD"]},"resources.index":{"uri":"resources\/{resType}","methods":["GET","HEAD"],"parameters":["resType"]},"resources.store":{"uri":"resources\/{resType}","methods":["POST"],"parameters":["resType"]},"resources.show":{"uri":"resources\/{resType}\/{id}","methods":["GET","HEAD"],"parameters":["resType","id"]},"resources.update":{"uri":"resources\/{resType}\/{id}","methods":["PUT"],"parameters":["resType","id"]},"resources.destroy":{"uri":"resources\/{resType}\/{id}","methods":["DELETE"],"parameters":["resType","id"]},"expertsystems.index":{"uri":"expertsystems","methods":["GET","HEAD"]},"ruleperesepan.store":{"uri":"rule-peresepan-obat\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"get.keluhan":{"uri":"get-keluhan\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"get.alergi":{"uri":"get-alergi\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"get.diagnosa":{"uri":"get-diagnosa\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"status.kehamilan":{"uri":"status-kehamilan\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"get.medicationReq":{"uri":"get-medication-req\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"get.umur":{"uri":"kategori-umur\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"get.dataFisik":{"uri":"data-fisik\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"ruleperesepan.show":{"uri":"rule\/{rule}\/{id}","methods":["GET","HEAD"],"parameters":["rule","id"]},"search.medicationOrg":{"uri":"medicationOrg","methods":["GET","HEAD"]},"showForConditionPatient":{"uri":"getConditionPatient\/{section}\/{id}","methods":["GET","HEAD"],"parameters":["section","id"]},"request-to-stock":{"uri":"request-to-stock","methods":["GET","HEAD"]},"request-to-stock.store":{"uri":"store-request-stok","methods":["POST"]},"medicine.index":{"uri":"medicine","methods":["GET","HEAD"]},"medicine.store":{"uri":"medicine","methods":["POST"]},"medicine.show":{"uri":"medicine\/{medicine_code}","methods":["GET","HEAD"],"parameters":["medicine_code"]},"medicine.update":{"uri":"medicine\/{medicine_code}","methods":["PUT"],"parameters":["medicine_code"]},"medicine.destroy":{"uri":"medicine\/{medicine_code}","methods":["DELETE"],"parameters":["medicine_code"]},"medicationDispense.index":{"uri":"medicationDispense","methods":["GET","HEAD"]},"medicationDispense.show":{"uri":"medicationDispense\/{medicationReq_id}","methods":["GET","HEAD"],"parameters":["medicationReq_id"]},"medicationDispense.update":{"uri":"medicationDispense\/{medicationReq_id}","methods":["PUT"],"parameters":["medicationReq_id"]},"register":{"uri":"register","methods":["GET","HEAD"]},"login":{"uri":"login","methods":["GET","HEAD"]},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"]},"password.email":{"uri":"forgot-password","methods":["POST"]},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"],"parameters":["token"]},"password.store":{"uri":"reset-password","methods":["POST"]},"verification.notice":{"uri":"verify-email","methods":["GET","HEAD"]},"verification.verify":{"uri":"verify-email\/{id}\/{hash}","methods":["GET","HEAD"],"parameters":["id","hash"]},"verification.send":{"uri":"email\/verification-notification","methods":["POST"]},"password.confirm":{"uri":"confirm-password","methods":["GET","HEAD"]},"password.update":{"uri":"password","methods":["PUT"]},"logout":{"uri":"logout","methods":["POST"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };
