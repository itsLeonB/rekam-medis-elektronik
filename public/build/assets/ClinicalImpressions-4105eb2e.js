import{o as e,b as o,F as c,aT as r,f as n,a as s,t as i}from"./app-f0d7f9c0.js";const l={key:0},d=s("h1",{class:"text-lg font-semibold text-secondhand-orange-300"},"Prognosis",-1),p={class:"w-full mx-auto text-base text-left text-neutral-grey-200"},_={class:"w-full"},h={class:"bg-original-white-0"},w=s("th",{scope:"row",class:"px-6 py-4 font-semibold whitespace-nowrap w-1/4"}," Deskripsi ",-1),g={key:0,class:"px-6 py-4 w-3/4"},y={class:"bg-original-white-0"},b=s("th",{scope:"row",class:"px-6 py-4 font-semibold whitespace-nowrap w-1/4"}," Investigasi ",-1),u={key:0,class:"px-6 py-4 w-3/4"},x={class:"bg-original-white-0"},f=s("th",{scope:"row",class:"px-6 py-4 font-semibold whitespace-nowrap w-1/4"}," Temuan ",-1),m={key:0,class:"px-6 py-4 w-3/4"},k={class:"bg-original-white-0"},C=s("th",{scope:"row",class:"px-6 py-4 font-semibold whitespace-nowrap w-1/4"}," Hasil Prognosis ",-1),j={key:0,class:"px-6 py-4 w-3/4"},v={class:"bg-original-white-0"},B=s("th",{scope:"row",class:"px-6 py-4 font-semibold whitespace-nowrap w-1/4"}," Ringkasan ",-1),D={key:0,class:"px-6 py-4 w-3/4"},P={__name:"ClinicalImpressions",props:{object:{type:Object,required:!1}},setup(a){return(F,I)=>a.object?(e(),o("div",l,[d,(e(!0),o(c,null,r(a.object,t=>(e(),o("table",p,[s("tbody",_,[s("tr",h,[w,t.description?(e(),o("td",g,i(t.description),1)):n("",!0)]),s("tr",y,[b,t.investigation?(e(),o("td",u,i(t.investigation[0].code.coding[0].display),1)):n("",!0)]),s("tr",x,[f,t.finding?(e(),o("td",m,i(t.finding[0].itemCodeableConcept.coding[0].display),1)):n("",!0)]),s("tr",k,[C,t.prognosisCodeableConcept?(e(),o("td",j,i(t.prognosisCodeableConcept[0].coding[0].display),1)):n("",!0)]),s("tr",v,[B,t.summary?(e(),o("td",D,i(t.summary),1)):n("",!0)])])]))),256))])):n("",!0)}};export{P as default};
