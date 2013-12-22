create database company;
use company;

create table department(primary key (department_id), department_id varchar(5) , salary int(6), classification varchar(25));
create table car(primary key (car_model), car_model varchar(6),size int(3), description varchar(50));
create table people(primary key (staff_id),staff_id varchar(4), name varchar(10), dob varchar(12));

create table trip(primary key (trip_id),foreign key(department_id) references department(department_id), trip_id varchar(5), department_id varchar(5));


create table manager(primary key (staff_id), foreign key (staff_id) references people(staff_id), staff_id varchar(4), title varchar(25));
create table engineer(primary key (staff_id),foreign key (staff_id) references people(staff_id), staff_id varchar(4), title varchar(25));
create table car_rental(primary key (car_model,staff_id,trip_id), foreign key (car_model) references car(car_model),foreign key (staff_id) references people(staff_id),foreign key (trip_id) references trip(trip_id),car_model varchar(6), staff_id varchar(4), trip_id varchar(5));

insert into department values("aa001", 35670, "class a section manager");
insert into department values("ab001", 30010, "class a group manager");
insert into department values("ba001", 22500, "class b section manager");
insert into department values("ac001", 0, "class a group manager");
insert into department values("bb001", 0, "class b group manager");
insert into department values("bc001", 0, "class b group manager");

insert into car values("sa-38", 165, "long car (douglas)");
insert into car values("mz-18", 120, "small sportier");
insert into car values("r-023", 150, "long car (rover)");

insert into people values("a001", "alexander", "07/01/1962");
insert into people values("a002", "april", "05/24/1975");
insert into people values("b001", "bobby", "12/06/1984");
insert into people values("b002", "bladder", "01/03/1980");
insert into people values("b003", "brent", "12/15/1979");
insert into people values("b004", "belandar", "08/18/1963");
insert into people values("c001", "calvin", "04/03/1977");
insert into people values("c002", "chevron", "02/02/1974");


insert into trip values("t0001","aa001");
insert into trip values("t0002","aa001");
insert into trip values("t0003","ab001");
insert into trip values("t0004","ba001");

insert into manager values("a002", "sales manager");
insert into manager values("b001", "marketing manager");
insert into manager values("b002", "account manager");
insert into manager values("c001", "general manager");

insert into engineer values("a001", "electronic engineer");
insert into engineer values("b003", "senior engineer");
insert into engineer values("b004", "electronic engineer");
insert into engineer values("c002", "junior engineer");

insert into car_rental values("mz-18","a002","t0001");
insert into car_rental values("mz-18","b001","t0002");
insert into car_rental values("r-023","b004","t0001");
insert into car_rental values("r-023","c001","t0004");
insert into car_rental values("sa-38","a001","t0003");
insert into car_rental values("sa-38","a002","t0001");