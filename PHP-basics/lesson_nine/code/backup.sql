--
-- PostgreSQL database dump
--

-- Dumped from database version 17.2 (Debian 17.2-1.pgdg120+1)
-- Dumped by pg_dump version 17.2 (Debian 17.2-1.pgdg120+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: user_roles; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.user_roles (
    id_user_role integer NOT NULL,
    id_user integer,
    role character varying(45) DEFAULT NULL::character varying
);


ALTER TABLE public.user_roles OWNER TO root;

--
-- Name: user_roles_id_user_role_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.user_roles_id_user_role_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_roles_id_user_role_seq OWNER TO root;

--
-- Name: user_roles_id_user_role_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.user_roles_id_user_role_seq OWNED BY public.user_roles.id_user_role;


--
-- Name: users; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.users (
    id_user integer NOT NULL,
    name character varying(50),
    lastname character varying(50),
    password_hash character varying(255),
    login character varying(50),
    password character varying(255),
    user_birthday date,
    user_birthday_timestamp integer
);


ALTER TABLE public.users OWNER TO root;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO root;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id_user;


--
-- Name: user_roles id_user_role; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.user_roles ALTER COLUMN id_user_role SET DEFAULT nextval('public.user_roles_id_user_role_seq'::regclass);


--
-- Name: users id_user; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.users ALTER COLUMN id_user SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: user_roles; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.user_roles (id_user_role, id_user, role) FROM stdin;
8	1	admin
9	2	viewer
10	1	admin
11	2	user
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.users (id_user, name, lastname, password_hash, login, password, user_birthday, user_birthday_timestamp) FROM stdin;
1	Jane	Doe	efd5e6a4054aa9f34e16dc7de104072d	jane_doe	password123	\N	\N
2	John	Doe	hashed_password	johndoe	\N	1990-01-01	\N
3	John	Doe	$2y$10$eImiTXuWVxfM37uY4m1z5uW1Q9Y9f6j8s5g8g5s5g5g5s5g5s5g5g	johndoe	\N	\N	\N
4	Jane	Doe	$2y$10$eImiTXuWVxfM37uY4m1z5uW1Q9Y9f6j8s5g8g5s5g5g5s5g5s5g5g	janedoe	\N	\N	\N
\.


--
-- Name: user_roles_id_user_role_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.user_roles_id_user_role_seq', 11, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.users_id_seq', 4, true);


--
-- Name: user_roles user_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.user_roles
    ADD CONSTRAINT user_roles_pkey PRIMARY KEY (id_user_role);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id_user);


--
-- Name: user_roles user_roles_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.user_roles
    ADD CONSTRAINT user_roles_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.users(id_user) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

