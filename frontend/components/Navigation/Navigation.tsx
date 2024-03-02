import { usePathname, useRouter } from "next/navigation";
import Container from "react-bootstrap/Container";
import Nav from "react-bootstrap/Nav";
import Navbar from "react-bootstrap/Navbar";
import { Logo } from "@/resources/images";
import Image from "next/image";
import styles from "./Navigation.module.scss";
import { NAV_LINKS } from "@/utils/constants";

const Navigation = () => {
    const link = usePathname();

    return (
        <Navbar expand="lg" className={styles.nav} data-bs-theme="dark">
            <Container>
                <Navbar.Brand href="/">
                    <div className={styles.nav_image}>
                        <Image src={Logo} alt="logo" height={200} width={200} />
                    </div>
                    <div className={styles.nav_brand}>
                        <h2>
                            IMS <span>since 2024.</span>
                        </h2>
                    </div>
                </Navbar.Brand>
                <Navbar.Toggle aria-controls="navigation" />
                <Navbar.Collapse id="navigation" className={styles.nav_links}>
                    <Nav>
                        {NAV_LINKS.map((item) => (
                            <Nav.Link
                                key={item.id}
                                href={item.link}
                                className={
                                    link?.includes(item.link)
                                        ? styles.nav_active
                                        : ""
                                }
                            >
                                {item.label}
                            </Nav.Link>
                        ))}
                    </Nav>
                </Navbar.Collapse>
            </Container>
        </Navbar>
    );
};

export default Navigation;
