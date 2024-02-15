import styles from "./Footer.module.scss";
import Image from "next/image";
import Link from "next/link";
import { FOOTER_SOCIAL_MEDIAS, FOOTER_LINKS } from "@/utils/constants";

const Footer = () => {
    const year = new Date().getFullYear();

    return (
        <footer className={styles.footer}>
            <div className="container">
                <div className={styles.footer_social}>
                    {FOOTER_SOCIAL_MEDIAS.map((item) => (
                        <Link href={item.link} target="_blank" key={item.id}>
                            <div className={styles.footer_social_icon}>
                                <Image
                                    src={item.icon}
                                    alt={item.socialMedia}
                                    width={20}
                                    height={20}
                                />
                            </div>
                        </Link>
                    ))}
                </div>

                <div className={styles.footer_links}>
                    {FOOTER_LINKS.map((item) => (
                        <Link href={item.link} key={item.id}>
                            {item.label}
                        </Link>
                    ))}
                </div>

                <div className={styles.footer_copyright}>
                    <p>Copyright © {year}; Developed by PROGRAMATORI</p>
                </div>
            </div>
        </footer>
    );
};

export default Footer;
