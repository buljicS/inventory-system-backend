"use client";
import { useState } from "react";
import { LOGIN_INPUTS } from "@/utils/constants";
import { TLoginData } from "@/utils/types";
import { Form, Button } from "react-bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import styles from "@/styles/Login.module.scss";
import axios from "axios";

const Login = () => {
    const [userData, setUserData] = useState<TLoginData>({
        email: "",
        password: "",
    });
    const [isLoading, setIsLoading] = useState<boolean>(false);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setUserData((prevUserData) => ({ ...prevUserData, [name]: value }));
    };

    const handleSubmit = async () => {
        try {
            setIsLoading(true);
            const response = await axios.post("", { userData });
        } catch (error) {
            console.log(error);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <section className={`${styles.login} container`}>
            <Form onSubmit={handleSubmit}>
                {LOGIN_INPUTS.map((input) => (
                    <Form.Group
                        className="mb-3"
                        controlId={"FormInput " + input.id}
                        key={input.id}
                    >
                        <Form.Label>{input.label}</Form.Label>
                        <Form.Control
                            type={input.type}
                            placeholder={input.placeholder}
                            name={input.name}
                            value={userData[input.name]}
                            onChange={handleChange}
                        />
                    </Form.Group>
                ))}
                <Button type="submit">Submit</Button>
            </Form>
        </section>
    );
};

export default Login;
