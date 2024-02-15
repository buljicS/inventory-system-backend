import styles from "./IndexCard.module.scss";
const IndexCard = ({ content }) => {
    return (
        <div className={styles.index_card}>
            <div className={styles.index_card_header}>
                <h1>{content.header}</h1>
                <h2>{content.subheader}</h2>
            </div>

            {content.steps && (
                <div className={styles.index_card_bottom}>
                    {content.steps.map((item, index) => (
                        <div
                            className={styles.index_card_bottom_steps}
                            key={index}
                        >
                            <div
                                className={
                                    styles.index_card_bottom_steps_number
                                }
                            >
                                {index + 1}
                            </div>
                            <div
                                className={styles.index_card_bottom_steps_text}
                            >
                                {item}
                            </div>
                        </div>
                    ))}
                </div>
            )}

            {content.text && (
                <div className={styles.index_card_bottom}>{content.text}</div>
            )}
        </div>
    );
};

export default IndexCard;
