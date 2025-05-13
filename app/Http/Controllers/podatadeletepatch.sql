select * from tblGoodsinwarddet where workorderno='15419215'
select * from tblGoodsinwarddet where workorderno='15419221'

insert into tblGoodsinwarddet_d(goodsinward_id,workorderno,itemno,pitemno,pcno,netwt,qty)
select goodsinward_id,workorderno,itemno,pitemno,pcno,netwt,qty from tblGoodsinwarddet where workorderno in ('15419215','15419221')

insert into tblDEPL_packingProd_d(
  tid,tdate,workorderno,itemno,qty,ExcessQty,rmdept,departmentto,
  pono,itemstage,fcode,user_id,created_at,pcno,vchrno
 ) select id,tdate,workorderno,itemno,qty,ExcessQty,rmdept,departmentto,
  pono,itemstage,fcode,user_id,created_at,pcno,vchrno
 from tblDEPL_packingprod where workorderno in ('15419215','15419221')

 insert into tblDEPL_packingcon_d(
  tid,tdate,workorderno,itemno,qty,ExcessQty,dept,deptto,
  pono,itemstage,fcode,user_id,created_at,lotno
 ) select id,tdate,workorderno,itemno,qty,ExcessQty,dept,deptto,
  pono,itemstage,fcode,user_id,created_at,lotno
 from tblDEPL_packingcon where workorderno in ('15419215','15419221')

 insert into tblGoodsoutwarddet_d(goodsoutward_id,workorderno,itemno,pitemno,pcno,netwt,qty)
 select goodsoutward_id,workorderno,itemno,pitemno,pcno,netwt,qty from tblGoodsoutwarddet where workorderno in ('15419215','15419221')

delete from tblGoodsinwarddet where workorderno in ('15419215','15419221')
delete from tblDepl_packingprod where workorderno in ('15419215','15419221')
delete from tblDepl_packingcon where workorderno in ('15419215','15419221')
delete from tblGoodsoutwarddet where workorderno in ('15419215','15419221')