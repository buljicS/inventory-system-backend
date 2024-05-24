import styles from "./FormSubmit.module.scss";
import Spinner from "react-bootstrap/Spinner";
import { Button } from "react-bootstrap";

const FormSubmit = ({ isLoading, value }) => {
    return (
        <Button type="submit" className={styles.button}>
            {isLoading ? <Spinner animation="border" size="sm" /> : value}
        </Button>
    );
};

export default FormSubmit;
