import { Form } from "react-bootstrap";
import styles from "./FormInput.module.scss";
import Image from "next/image";
import {
    Tooltip,
    useDisclosure,
    NumberInput,
    NumberInputField,
} from "@chakra-ui/react";
import { ErrorIcon } from "@/resources/icons";

const FormNumberInput = ({ input, errors, register }) => {
    const { isOpen, onOpen, onToggle, onClose } = useDisclosure();

    return (
        <Form.Group
            className="mb-3 active-border"
            controlId={input.name + input.id}
            key={input.id}
        >
            <Form.Label>{input.label}</Form.Label>
            <div className={styles.input}>
                <NumberInput min={1}>
                    <NumberInputField
                        placeholder={input.placeholder}
                        {...register(input.name)}
                        className={styles.active_border}
                    />
                </NumberInput>
                <div className={styles.input_error}>
                    {errors[input.name] && (
                        <Tooltip
                            label={errors[input.name].message}
                            fontSize="sm"
                            bg="red.600"
                            textAlign="center"
                            isOpen={isOpen}
                            placement="auto"
                        >
                            <Image
                                src={ErrorIcon}
                                width={20}
                                height={20}
                                alt="Error"
                                onMouseEnter={onOpen}
                                onMouseLeave={onClose}
                                onClick={onToggle}
                            />
                        </Tooltip>
                    )}
                </div>
            </div>
        </Form.Group>
    );
};

export default FormNumberInput;
