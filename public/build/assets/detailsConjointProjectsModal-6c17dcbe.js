import{m as i,a as _}from"./alertModal-064cb8ca.js";import{m as d}from"./mixins-00a35343.js";import{o as e,h as r,i as f,b as s,j as c,a as l,t as o,T as m}from"./index-a6b313c0.js";import{_ as y}from"./_plugin-vue_export-helper-c27b6911.js";const h={components:{modalconfirmacion:i,modalalerta:_},mixins:[d],data:()=>({}),props:{project1:Object},created(){}},b={name:"modal"},u={class:"modal-mask"},p={class:"modal-wrapper"},v={class:"modal-container"},x={class:"modal-header pb-1",style:{"font-weight":"bold",color:"#444444"}},j=s("label",{class:"col-5 m-0",style:{"font-size":"17px"}},"Conjoint Project Details",-1),k={class:"modal-body"},w={class:"form-group"},z={class:"row"},g=s("div",{class:"text-uppercase pb-4"},"General information:",-1),D={class:"col-6 m-0 fs-8 pb-1"},C=s("label",null,"Status: ",-1),O={key:0,class:"fw-normal",style:{"font-size":"14px"}},R={key:1,class:"fw-normal",style:{"font-size":"14px"}},B={class:"col-6 m-0 fs-8 pb-1"},S=s("label",null,"Institution Collaborates: ",-1),E={key:0,class:"fw-normal",style:{"font-size":"14px"}},N={key:1,class:"fw-normal",style:{"font-size":"14px"}},T={class:"col-6 m-0 fs-8 pb-1"},I=s("label",null,"Researcher Involved: ",-1),A={key:0,class:"fw-normal",style:{"font-size":"14px"}},P={key:1,class:"fw-normal",style:{"font-size":"14px"}},G={class:"col-6 m-0 fs-8 pb-1"},V=s("label",null,"Activity Type: ",-1),X={key:0,class:"fw-normal",style:{"font-size":"14px"}},q={key:1,class:"fw-normal",style:{"font-size":"14px"}},F={class:"col-6 m-0 fs-8 pb-1"},H=s("label",null,"Activity Name: ",-1),J={key:0,class:"fw-normal",style:{"font-size":"14px"}},K={key:1,class:"fw-normal",style:{"font-size":"14px"}},L={class:"col-6 m-0 fs-8 pb-1"},M=s("label",null,"Country Origin: ",-1),Q={key:0,class:"fw-normal",style:{"font-size":"14px"}},U={key:1,class:"fw-normal",style:{"font-size":"14px"}},W={class:"col-6 m-0 fs-8 pb-1"},Y=s("label",null,"City Origin: ",-1),Z={key:0,class:"fw-normal",style:{"font-size":"14px"}},$={key:1,class:"fw-normal",style:{"font-size":"14px"}},ss={class:"col-6 m-0 fs-8 pb-1"},es=s("label",null,"Country Destination: ",-1),ts={key:0,class:"fw-normal",style:{"font-size":"14px"}},ls={key:1,class:"fw-normal",style:{"font-size":"14px"}},os={class:"col-6 m-0 fs-8 pb-1"},ns=s("label",null,"City Destination: ",-1),as={key:0,class:"fw-normal",style:{"font-size":"14px"}},cs={key:1,class:"fw-normal",style:{"font-size":"14px"}},is={class:"col-6 m-0 fs-8 pb-1"},_s=s("label",null,"Beginning Date: ",-1),ds={key:0,class:"fw-normal",style:{"font-size":"14px"}},rs={key:1,class:"fw-normal",style:{"font-size":"14px"}},fs={class:"col-6 m-0 fs-8 pb-1"},ms=s("label",null,"Ending Date: ",-1),ys={key:0,class:"fw-normal",style:{"font-size":"14px"}},hs={key:1,class:"fw-normal",style:{"font-size":"14px"}},bs={class:"col-6 m-0 fs-8 pb-1"},us=s("label",null,"Progress Report: ",-1),ps={key:0,class:"fw-normal",style:{"font-size":"14px"}},vs={key:1,class:"fw-normal",style:{"font-size":"14px"}},xs={class:"col-6 m-0 fs-8 pb-1"},js=s("label",null,"Name of External Researcher: ",-1),ks={key:0,class:"fw-normal",style:{"font-size":"14px"}},ws={key:1,class:"fw-normal",style:{"font-size":"14px"}},zs={class:"col-6 m-0 fs-8 pb-1"},gs=s("label",null,"Collaboration Stay: ",-1),Ds={key:0,class:"fw-normal",style:{"font-size":"14px"}},Cs={key:1,class:"fw-normal",style:{"font-size":"14px"}},Os={class:"col-6 m-0 fs-8 pb-1"},Rs=s("label",null,"Comments: ",-1),Bs={key:0,class:"fw-normal",style:{"font-size":"14px"}},Ss={key:1,class:"fw-normal",style:{"font-size":"14px"}};function Es(n,a,t,Ns,Ts,Is){return e(),r(m,{name:"modalver"},{default:f(()=>[s("div",b,[s("div",u,[s("div",p,[s("div",v,[s("div",x,[c(n.$slots,"header",{},()=>[j]),s("a",{class:"btn btn-closed",onClick:a[0]||(a[0]=As=>n.$emit("close")),ref:"closeBtn"},"X",512)]),s("div",k,[c(n.$slots,"body",{},()=>[s("div",w,[s("div",z,[g,s("div",D,[C,t.project1.status!=null?(e(),l("label",O,o(t.project1.status),1)):(e(),l("label",R,"---"))]),s("div",B,[S,t.project1.institutionCollaborates!=null?(e(),l("label",E,o(t.project1.institutionCollaborates),1)):(e(),l("label",N,"---"))]),s("div",T,[I,t.project1.researcherInvolved!=null?(e(),l("label",A,o(t.project1.researcherInvolved),1)):(e(),l("label",P,"---"))]),s("div",G,[V,t.project1.activityType!=null?(e(),l("label",X,o(t.project1.activityType),1)):(e(),l("label",q,"---"))]),s("div",F,[H,t.project1.activityName!=null?(e(),l("label",J,o(t.project1.activityName),1)):(e(),l("label",K,"---"))]),s("div",L,[M,t.project1.countryOrigin!=null?(e(),l("label",Q,o(t.project1.countryOrigin),1)):(e(),l("label",U,"---"))]),s("div",W,[Y,t.project1.cityOrigin!=null?(e(),l("label",Z,o(t.project1.cityOrigin),1)):(e(),l("label",$,"---"))]),s("div",ss,[es,t.project1.countryDestination!=null?(e(),l("label",ts,o(t.project1.countryDestination),1)):(e(),l("label",ls,"---"))]),s("div",os,[ns,t.project1.cityDestination!=null?(e(),l("label",as,o(t.project1.cityDestination),1)):(e(),l("label",cs,"---"))]),s("div",is,[_s,t.project1.beginningDate!=null?(e(),l("label",ds,o(t.project1.beginningDate),1)):(e(),l("label",rs,"---"))]),s("div",fs,[ms,t.project1.endingDate!=null?(e(),l("label",ys,o(t.project1.endingDate),1)):(e(),l("label",hs,"---"))]),s("div",bs,[us,t.project1.progressReport!=null?(e(),l("label",ps,o(t.project1.progressReport),1)):(e(),l("label",vs,"---"))]),s("div",xs,[js,t.project1.nameOfExternalResearcher!=null?(e(),l("label",ks,o(t.project1.nameOfExternalResearcher),1)):(e(),l("label",ws,"---"))]),s("div",zs,[gs,t.project1.collaborationStay!=null?(e(),l("label",Ds,o(t.project1.collaborationStay),1)):(e(),l("label",Cs,"---"))]),s("div",Os,[Rs,t.project1.comments!=null?(e(),l("label",Bs,o(t.project1.comments),1)):(e(),l("label",Ss,"---"))])])])])])])])])])]),_:3})}const qs=y(h,[["render",Es]]);export{qs as m};
